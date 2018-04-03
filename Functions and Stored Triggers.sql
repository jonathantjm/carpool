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

-- update bids and offer status - stored procedure
CREATE OR REPLACE FUNCTION updateBidsAndOfferStatus()
RETURNS TRIGGER AS $bid_table$
DECLARE
	_email varchar;
	_status varchar;
	_price numeric;
	_creationDateTime timestamp;
	_startLocation varchar;
	_endLocation varchar;
	_pickUpDate date;
	_pickUpTime time;
BEGIN
    IF NEW.status = 'Accepted' THEN		
        UPDATE bid SET status = 'Rejected' WHERE status = 'Pending' AND advertisementID = NEW.advertisementID;
--		_email = SELECT email FROM bid WHERE advertisementID = NEW.advertisementID AND status = NEW.status;--
--		_status = SELECT status FROM bid WHERE advertisementID = NEW.advertisementID AND status = NEW.status;
--		_price = SELECT price FROM bid WHERE advertisementID = NEW.advertisementID AND status = NEW.status;
--		_creationDateTime = SELECT creation_date_and_time FROM bid WHERE advertisementID = NEW.advertisementID AND status = NEW.status;
--		_startLocation = SELECT start_location FROM advertisements WHERE advertisementID = NEW.advertisementID;
--		_endLocation = SELECT end_location FROM advertisements WHERE advertisementID = NEW.advertisementID;
--		_pickUpDate = SELECT date_of_pickup FROM advertisements WHERE advertisementID = NEW.advertisementID;
--		_pickUpTime = SELECT time_of_pickup FROM advertisements WHERE advertisementID = NEW.advertisementID;
--		INSERT INTO bidHistory(email, status, price, creation_date_and_time, start_location, end_location, date_of_pickup, time_of_pickup)
--			VALUES(_email, _status, _price, _creationDateTime, _startLocation, _endLocation, _pickUpDate, _pickUpTime);
--		DELETE FROM bid WHERE advertisementID = NEW.advertisementID;
        UPDATE advertisements SET closed = true WHERE advertisementID = NEW.advertisementID;
        INSERT INTO advertisementsHistory(email_of_driver, start_location, end_location, creation_date_and_time, date_of_pickup, time_of_pickup, self_select) 
            SELECT email_of_driver, start_location, end_location, creation_date_and_time, date_of_pickup, time_of_pickup, self_select FROM advertisements WHERE advertisementID = NEW.advertisementID;
        DELETE FROM advertisements WHERE advertisementID = NEW.advertisementID;
	ELSEIF NEW.status = 'Rejected' OR NEW.status = 'Retracted' THEN
		_email = NEW.email;
		_status = SELECT status FROM bid WHERE advertisementID = NEW.advertisementID AND email = NEW.email;
		_price = SELECT price FROM bid WHERE advertisementID = NEW.advertisementID AND email = NEW.email;
		_creationDateTime = SELECT creation_date_and_time FROM bid WHERE email = NEW.email AND advertisementID = NEW.advertisementID;
		_startLocation = SELECT start_location FROM advertisements WHERE email = NEW.email AND advertisementID = NEW.advertisementID;
		_endLocation = SELECT end_location FROM advertisements WHERE advertisementID = NEW.advertisementID;
		_pickUpDate = SELECT date_of_pickup FROM advertisements WHERE advertisementID = NEW.advertisementID;
		_pickUpTime = SELECT time_of_pickup FROM advertisements WHERE advertisementID = NEW.advertisementID;
		INSERT INTO bidHistory(email, status, price, creation_date_and_time, start_location, end_location, date_of_pickup, time_of_pickup)
			VALUES(_email, _status, _price, _creationDateTime, _startLocation, _endLocation, _pickUpDate, _pickUpTime);
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
	error3 varchar := 'Status is case-sensitive and should be Pending, Accepted, Expired or Rejected';
	error4 varchar := 'Price should be numeric and greater than 0!';
	message varchar := '';
BEGIN
	IF NOT EXISTS (SELECT email FROM useraccount WHERE email = _email)
		THEN message := error1;
	ELSEIF NOT EXISTS (SELECT advertisementid FROM advertisements WHERE advertisementid = _advertisementID)
		THEN message := error2;
	ELSEIF (_status <> 'Pending' AND _status <> 'Accepted' AND _status <> 'Expired' AND _status <> 'Rejected')
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
			UPDATE bid SET status = 'Retracted' WHERE advertisementID = NEW.advertisementID;
		ELSE
			UPDATE bid SET status = 'Rejected' WHERE advertisementID = NEW.advertisementID;
		END IF;
	END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Function to retract bid when EITHER admin OR user delete an existing 'Pending' bid
CREATE OR REPLACE FUNCTION deleteBid(_email varchar, _advertisementID bigint) RETURNS void AS $$
BEGIN
	UPDATE bid SET status = 'Retracted' WHERE email = _email AND advertisementID = _advertisementID;
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