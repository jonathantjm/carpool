-- add a user
CREATE OR REPLACE FUNCTION add_user(_name varchar, _gender varchar, _contact varchar, _email varchar, _password varchar, 
    _platenumber varchar, _capacity integer, _isDriver boolean, _isAdmin boolean)
returns varchar as
$BODY$
--DECLARE result varchar;
--DECLARE idvar integer;
BEGIN
    IF EXISTS (SELECT email FROM useraccount WHERE email = _email) THEN return 'Email already exists!';
END IF;
IF EXISTS (SELECT vehicle_plate FROM useraccount WHERE vehicle_plate = _platenumber) THEN return 'Plate number already exists!';
END IF;
INSERT INTO useraccount VALUES (_name, _gender, _contact, _email, _password, _platenumber, _capacity, _isDriver, _isAdmin);
return 'Account has been successfully created!';
END;
$BODY$
language 'plpgsql' volatile;

-- delete advertisement and place deleted advertisement into advertisementsHistory
CREATE OR REPLACE FUNCTION delete_advertisement(_advertisementID bigint)
returns varchar as
$BODY$
BEGIN
IF EXISTS (SELECT * FROM advertisements WHERE advertisementID = _advertisementID) THEN
UPDATE advertisements SET closed = true WHERE advertisementID = _advertisementID;
INSERT INTO advertisementsHistory(email_of_driver, start_location, end_location, creation_date_and_time, date_of_pickup, time_of_pickup, self_select)
    SELECT email_of_driver, start_location, end_location, creation_date_and_time, date_of_pickup, time_of_pickup, self_select FROM advertisements WHERE advertisementID = _advertisementID;
DELETE FROM advertisements WHERE advertisementID = _advertisementID;
return _advertisementID|| 'Plate number already exists!';
END IF;
END;
$BODY$
language 'plpgsql' volatile;

-- update bids and offer status - stored procedure -- trigger AFTER UPDATE on bid
CREATE OR REPLACE FUNCTION updateBidsAndOfferStatus()
RETURNS TRIGGER AS $bid_table$
BEGIN
    IF NEW.status = 'Accepted' THEN
        -- this part settles the bids
        UPDATE bid SET status = 'Rejected' WHERE status = 'Pending' AND advertisementID = NEW.advertisementID;
        INSERT INTO bidHistory(email, status, price, creation_date_and_time, start_location, end_location, date_of_pickup, time_of_pickup)
            SELECT B1.email, B1.status, B1.price, B1.creation_date_and_time, A1.start_location, A1.end_location, A1.date_of_pickup, A1.time_of_pickup FROM bid B1, advertisements A1 WHERE B1.advertisementID = A1.advertisementID AND A1.advertisementID = NEW.advertisementID;
        DELETE FROM bid WHERE advertisementID = NEW.advertisementID;
        -- this part settles the advertisements
        UPDATE advertisements SET closed = true WHERE advertisementID = NEW.advertisementID;
        INSERT INTO advertisementsHistory(email_of_driver, start_location, end_location, creation_date_and_time, date_of_pickup, time_of_pickup, self_select) 
            SELECT email_of_driver, start_location, end_location, creation_date_and_time, date_of_pickup, time_of_pickup, self_select FROM advertisements WHERE advertisementID = NEW.advertisementID;
        DELETE FROM advertisements WHERE advertisementID = NEW.advertisementID;
	ELSEIF NEW.status = 'Rejected' OR NEW.status = 'Offer retracted' OR NEW.status = 'Offer expired' THEN
        INSERT INTO bidHistory(email, status, price, creation_date_and_time, start_location, end_location, date_of_pickup, time_of_pickup)
            SELECT B1.email, B1.status, B1.price, B1.creation_date_and_time, A1.start_location, A1.end_location, A1.date_of_pickup, A1.time_of_pickup FROM bid B1, advertisements A1 WHERE B1.advertisementID = A1.advertisementID AND A1.advertisementID = NEW.advertisementID;
		DELETE FROM bid WHERE advertisementID = NEW.advertisementID AND email = NEW.email;
   END IF;
RETURN NEW;
END;
$bid_table$ LANGUAGE plpgsql;

    /*
    CREATE OR REPLACE FUNCTION add_offer(_advertisementID integer, _email varchar, _start varchar, _end varchar, _creationDateTime timestamp, _pickupDate date, _pickupTime time, _driverSelfSelect boolean)
    returns varchar as
    $BODY$
    BEGIN
        IF NOT EXISTS (SELECT email FROM useraccount WHERE email = _email) THEN return 'User account for this email does not exist!';
END IF;
INSERT INTO advertisements VALUES (_advertisementID, _email, _start, _end, _creationDateTime, _pickupDate, _pickupTime, DEFAULT, _driverSelfSelect);
END;
$BODY$
language 'plpgsql' volatile;
*/

--Function for admin to add new bid
CREATE OR REPLACE FUNCTION admin_addBid(_email varchar, _advertisementID bigint, _price numeric, _creationDateTime TIMESTAMP) RETURNS varchar AS $$
DECLARE
    error1 varchar := 'User email is invalid!';
	error2 varchar := 'Advertisement id does not exist!';
    error3 varchar := 'Price should be numeric and greater than 0!';
	message varchar := '';
BEGIN
	IF NOT EXISTS (SELECT email FROM useraccount WHERE email = _email)
		THEN message := error1;
	ELSEIF NOT EXISTS (SELECT advertisementid FROM advertisements WHERE advertisementid = _advertisementID)
		THEN message := error2;
	ELSEIF (_price <= 0)
		THEN message := error3;
	ELSE
		INSERT INTO bid(email, advertisementid, price, creation_date_and_time) VALUES(_email, _advertisementID, _price, _creationDateTime);
	END IF;
	RETURN message;
END;
$$ LANGUAGE plpgsql;

--Function for admin to edit bid
CREATE OR REPLACE FUNCTION admin_editBid(_oldEmail varchar, _oldID integer, _email varchar, _advertisementID bigint, _status varchar, _price numeric, _creationDateTime TIMESTAMP) RETURNS varchar AS $$
DECLARE
	error1 varchar := 'User email is invalid!';
	error2 varchar := 'Advertisement id does not exist!';
	error3 varchar := 'Status is case-sensitive and should be Pending, Rejected, Accepted,  Offer retracted or Offer expired';
	error4 varchar := 'Price should be numeric and greater than 0!';
	message varchar := '';
BEGIN
	IF NOT EXISTS (SELECT email FROM useraccount WHERE email = _email)
		THEN message := error1;
	ELSEIF NOT EXISTS (SELECT advertisementid FROM advertisements WHERE advertisementid = _advertisementID)
		THEN message := error2;
	ELSEIF (_status <> 'Pending' AND _status <> 'Rejected' AND _status <> 'Accepted' AND _status <> 'Expired' AND _status <> 'Rejected')
		THEN message := error3;
	ELSEIF (_price <= 0)
		THEN message := error4;
	ELSE
		UPDATE bid SET email = _email, advertisementid = _advertisementID, status = _status, price = _price, creation_date_and_time = _creationDateTime WHERE advertisementid = _oldID AND email = _oldEmail;
	END IF;
	RETURN message;
END;
$$ LANGUAGE plpgsql;

--Procedure to retract/reject bid after ad has been closed
--Bid set to Retracted if current date is BEFORE the date of pickup
--Bid set to Expired if current date is AFTER the date of pickup
CREATE OR REPLACE FUNCTION expireBidIfAdClosed() RETURNS TRIGGER AS $$
BEGIN
	IF NEW.closed IS TRUE THEN
		IF NEW.date_of_pickup > current_date THEN
			UPDATE bid SET status = 'Offer retracted' WHERE advertisementID = NEW.advertisementID;
		ELSE
			UPDATE bid SET status = 'Offer expired' WHERE advertisementID = NEW.advertisementID;
		END IF;
	END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Function to retract bid when EITHER admin OR user delete an existing 'Pending' bid
CREATE OR REPLACE FUNCTION deleteBid(_email varchar, _advertisementID bigint) RETURNS void AS $$
BEGIN
	UPDATE bid SET status = 'Offer retracted' WHERE email = _email AND advertisementID = _advertisementID;
END;
$$ LANGUAGE plpgsql;

--DELETE bid after UPDATE on advertisement
CREATE TRIGGER deleteBidAfterAdvertisement 
AFTER UPDATE
ON advertisements
FOR EACH ROW
EXECUTE PROCEDURE expireBidIfAdClosed();

--update bid status - trigger
CREATE TRIGGER updateBidsAndOffer 
AFTER UPDATE
ON bid
FOR EACH ROW
EXECUTE PROCEDURE updateBidsAndOfferStatus();
