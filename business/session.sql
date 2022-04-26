-- - Register client - --

CALL register_client(
        '1234',
        'Antonio Juan Sebastian',
        'UPB',
        '1234',
        'juan@upb.com',
        'my-password'
    );

-- - Login - --

SET @login_result = client_login(
        'juan@upb.com',
        'my-password'
    );