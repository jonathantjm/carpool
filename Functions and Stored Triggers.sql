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
