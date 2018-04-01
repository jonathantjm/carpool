CREATE OR REPLACE FUNCTION add_user(_name varchar, _gender varchar, _contact varchar, _email varchar, _password varchar, 
_platenumber varchar, _capacity integer, _isDriver boolean, _isAdmin boolean)
returns varchar as
$BODY$
--DECLARE result varchar;
--DECLARE idvar integer;
BEGIN
IF EXISTS (SELECT email FROM useraccount WHERE email = _email) THEN return _email||' already exists!';
END IF;
IF EXISTS (SELECT vehicle_plate FROM useraccount WHERE vehicle_plate = _platenumber)THEN return _platenumber||' already exists!';
END IF;
INSERT INTO useraccount VALUES (_name, _gender, _contact, _email, _password, _platenumber, _capacity, _isDriver, _isAdmin);
--IF FOUND THEN return "Account has been successfully created!";
--ELSE return "Account was not created. Please check the values given!";
--END IF;
--IF array_length(idvar, 1) > 0 THEN return "Account has been successfully created!";
--ELSE return "Account was not created. Please check the values given!";
--END IF;
--SELECT _email INTO result;
return 'Account has been successfully created!';
END;
$BODY$
language 'plpgsql' volatile;

CREATE OR REPLACE FUNCTION admin_add_offer(_advertisementID integer, _email varchar, _start varchar, _end varchar, _creationDateTime timestamp, 
_pickupDate date, _pickupTime time, _driverSelfSelect boolean)
returns varchar as
$BODY$
--DECLARE result varchar;
--DECLARE idvar integer;
BEGIN
IF NOT EXISTS (SELECT email FROM useraccount WHERE email = _email) THEN return 'Account for ' ||_email||' does not exist!';
END IF;
INSERT INTO advertisements VALUES (_advertisementID, _email, _start, _end, _creationDateTime, _pickupDate, _pickupTime, DEFAULT, _driverSelfSelect);
--IF FOUND THEN return "Account has been successfully created!";
--ELSE return "Account was not created. Please check the values given!";
--END IF;
--IF array_length(idvar, 1) > 0 THEN return "Account has been successfully created!";
--ELSE return "Account was not created. Please check the values given!";
--END IF;
--SELECT _email INTO result;
--return 'Account has been successfully created!';
END;
$BODY$
language 'plpgsql' volatile;

CREATE OR REPLACE FUNCTION admin_addBid(_email varchar, _advertisementID integer, _price numeric, _creationDateTime TIMESTAMP) RETURNS varchar AS $$
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

CREATE OR REPLACE FUNCTION admin_editBid(_oldEmail varchar, _oldID integer, _email varchar, _advertisementID integer, _status varchar, _price numeric, _creationDateTime TIMESTAMP) RETURNS varchar AS $$
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
