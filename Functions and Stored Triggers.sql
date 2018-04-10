-- add a user
CREATE OR REPLACE FUNCTION add_user(_name varchar, _gender varchar, _contact varchar, _email varchar, _password varchar, 
    _platenumber varchar, _capacity integer, _isDriver boolean, _isAdmin boolean)
returns varchar as
$BODY$
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

--Check if the user has any existing offers
CREATE OR REPLACE FUNCTION checkExistingOffer(_currentEmail varchar) RETURNS boolean AS $$
DECLARE
BEGIN
    IF EXISTS (SELECT * FROM advertisements WHERE email_of_driver = _currentEmail) 
        THEN RETURN TRUE;
    END IF;
    RETURN FALSE;
END;
$$ LANGUAGE plpgsql;

-- add an advertisement
CREATE OR REPLACE FUNCTION add_advertisement(_email varchar, _startLocation varchar, _endLocation varchar, _pickupDate date, _pickupTime time, _selfSelect boolean)
returns varchar as
$BODY$
BEGIN
    IF NOT EXISTS (SELECT email FROM useraccount WHERE email = _email) THEN return 'Account for this email does not exist!';
    ELSEIF (_startLocation = _endLocation) THEN return 'Cannot have the same start and end location!';
    ELSEIF ((_pickupDate + _pickupTime) <= (current_timestamp + INTERVAL '1 hour')) THEN RETURN 'Pick-up date and time must be at least 1 hour from now!';
END IF;
INSERT INTO advertisements(email_of_driver, start_location, end_location, creation_date_and_time, date_of_pickup, time_of_pickup, self_select) VALUES (_email,_startLocation, _endLocation, current_timestamp, _pickupDate, _pickupTime, _selfSelect);
return '';
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
    ELSEIF NEW.status = 'Rejected' OR NEW.status = 'Offer retracted' THEN
        INSERT INTO bidHistory(email, status, price, creation_date_and_time, start_location, end_location, date_of_pickup, time_of_pickup)
        SELECT B1.email, B1.status, B1.price, B1.creation_date_and_time, A1.start_location, A1.end_location, A1.date_of_pickup, A1.time_of_pickup FROM bid B1, advertisements A1 WHERE B1.advertisementID = A1.advertisementID AND A1.advertisementID = NEW.advertisementID;
        DELETE FROM bid WHERE advertisementID = NEW.advertisementID AND email = NEW.email;
   END IF;
RETURN NULL;
END;
$bid_table$ LANGUAGE plpgsql;


--Function to add new bid
CREATE OR REPLACE FUNCTION addBid(_email varchar, _advertisementID bigint, _price numeric) RETURNS varchar AS $$
DECLARE
--own, email, id, price, submitted, closed
error0 varchar := 'You cannot bid for your own offer!';
error1 varchar := 'Your email is invalid!';
error2 varchar := 'Advertisement id does not exist!';
error3 varchar := 'Price should be numeric and greater than 0!';
error4 varchar := 'You have already submitted a bid for this offer!';
error5 varchar := 'Sorry, advertisement has already been closed!';
message varchar := '';
BEGIN
    IF EXISTS (SELECT email_of_driver FROM advertisements WHERE advertisementID = _advertisementID AND email_of_driver = _email)
        THEN message := error0;
    ELSEIF NOT EXISTS (SELECT email FROM useraccount WHERE email = _email)
        THEN message := error1;
    ELSEIF NOT EXISTS (SELECT advertisementid FROM advertisements WHERE advertisementid = _advertisementID)
        THEN message := error2;
    ELSEIF (_price <= 0)
        THEN message := error3;
    ELSEIF (SELECT COUNT(*) FROM bid WHERE email = _email AND advertisementID = _advertisementID) > 0
        THEN message := error4;
    ELSEIF (SELECT closed FROM advertisements WHERE advertisementID = _advertisementID) IS TRUE
        THEN message := error5;
    ELSE
        INSERT INTO bid(email, advertisementid, price, creation_date_and_time) VALUES(_email, _advertisementID, _price, current_timestamp);
END IF;
RETURN message;
END;
$$ LANGUAGE plpgsql;

--Function to edit bid
CREATE OR REPLACE FUNCTION editBid(_email varchar, _advertisementID bigint, _status varchar, _price numeric, _creationDateTime TIMESTAMP) RETURNS varchar AS $$
DECLARE
error0 varchar := 'You cannot bid for your own offer!';
error1 varchar := 'Your email is invalid!';
error2 varchar := 'Advertisement id does not exist!';
error3 varchar := 'Status is case-sensitive and should be Pending, Rejected, Accepted or Offer retracted';
error4 varchar := 'Price should be numeric and greater than 0!';
error5 varchar := 'Sorry, advertisement has already been closed!';
error6 varchar := 'Please make sure you have an existing bid for that offer!';
message varchar := '';
BEGIN
    IF EXISTS (SELECT email_of_driver FROM advertisements WHERE advertisementID = _advertisementID AND email_of_driver = _email)
        THEN message := error0;
    ELSEIF NOT EXISTS (SELECT email FROM useraccount WHERE email = _email)
        THEN message := error1;
    ELSEIF NOT EXISTS (SELECT advertisementid FROM advertisements WHERE advertisementid = _advertisementID)
        THEN message := error2;
    ELSEIF (_status <> 'Pending' AND _status <> 'Rejected' AND _status <> 'Accepted' AND _status <> 'Offer retracted')
        THEN message := error3;
    ELSEIF (_price <= 0)
        THEN message := error4;
    ELSEIF (SELECT closed FROM advertisements WHERE advertisementID = _advertisementID) IS TRUE
        THEN message := error5;
    ELSEIF NOT EXISTS (SELECT email, advertisementid FROM bid WHERE email = _email AND advertisementid = _advertisementID)
        THEN message := error6;
    ELSE
        UPDATE bid SET status = _status, price = _price, creation_date_and_time = _creationDateTime WHERE advertisementid = _advertisementID AND email = _email;
END IF;
RETURN message;
END;
$$ LANGUAGE plpgsql;

--Procedure to retract bid after ad has been closed
CREATE OR REPLACE FUNCTION expireBidIfAdClosed() RETURNS TRIGGER AS $$
BEGIN
    IF NEW.closed IS TRUE THEN
		UPDATE bid SET status = 'Offer retracted' WHERE advertisementID = NEW.advertisementID;
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


CREATE OR REPLACE FUNCTION updateAdvertisement()
RETURNS varchar AS $$
DECLARE
currentTimeAndDate timestamp := current_timestamp;
r1 advertisements%ROWTYPE;
r2 bid%ROWTYPE;
r3 advertisements%ROWTYPE;
BEGIN
	FOR r1 IN
	SELECT * FROM advertisements
	WHERE (date_of_pickup + time_of_pickup) <= (current_timestamp + INTERVAL '1 hour')
	AND self_select = FALSE
	LOOP
		FOR r2 IN
		SELECT * FROM bid
		WHERE bid.advertisementID = r1.advertisementID
		ORDER BY price DESC, creation_date_and_time
		LOOP
			UPDATE bid SET status = 'Accepted' WHERE bid.advertisementID = r2.advertisementID AND bid.email = r2.email;
		END LOOP;
	END LOOP;
	FOR r3 IN
    SELECT *
    FROM advertisements
    WHERE (date_of_pickup + time_of_pickup) <= (current_timestamp + INTERVAL '30 minutes')
    LOOP
        UPDATE advertisements SET closed = true WHERE advertisements.advertisementID = r3.advertisementID;
        INSERT INTO advertisementsHistory(email_of_driver, start_location, end_location, creation_date_and_time, date_of_pickup, time_of_pickup, self_select)
        SELECT email_of_driver, start_location, end_location, creation_date_and_time, date_of_pickup, time_of_pickup, self_select FROM advertisements WHERE advertisements.advertisementID = r3.advertisementID;
        DELETE FROM advertisements WHERE advertisements.advertisementID = r3.advertisementID;
	END LOOP;
RETURN 'Update complete';
END;
$$
LANGUAGE plpgsql;


