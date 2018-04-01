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

-- update bids and offer status - stored procedure
CREATE OR REPLACE FUNCTION updateBidsAndOfferStatus()
RETURNS TRIGGER AS $bid_table$
BEGIN
   IF NEW.status = 'Accepted' THEN
   UPDATE bid SET status = 'Rejected' WHERE status = 'Pending' AND advertisementID = NEW.advertisementID;
   UPDATE advertisements SET closed = true WHERE advertisementID = NEW.advertisementID;
   END IF;
   RETURN NEW;
END;
$bid_table$ LANGUAGE plpgsql;

--update bid status - trigger
CREATE TRIGGER updateBidsAndOffer 
AFTER UPDATE
ON bid
FOR EACH ROW
EXECUTE PROCEDURE updateBidsAndOfferStatus();

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

