CREATE TABLE payor.users (
    id varchar(36) PRIMARY KEY, -- dont do this irl ... use binary(16)
    username varchar(250) NOT NULL UNIQUE,
    password TEXT NOT NULL,
    api_key varchar(36) NOT NULL UNIQUE,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


INSERT INTO payor.users (id,username,password,api_key,is_active,created_at,updated_at) VALUES 
('e5d9a6b6-cc76-4832-9fd9-69b76fad7542','admin','$2y$10$CFXr3A.sjmM4EZKpScIYaernJxZ3amg0eoFgB0oThAH.vjupfbpTy','cd4898b3-167e-4f60-9832-242a41e2d0ba',true,'2019-03-28 18:16:37.710','2019-03-28 18:16:37.710')
;