
running process :

(a) composer install ; 
(b) cp .env.example .env ;

(c) php artisan key:generate ;
(d) php artisan jwt:secret ;
(e) php artisan cache:clear ; 
(f) php artisan config:clear ;

(g) then setup db and finally ;
(h) php artisan migrate ;

some issues and solutions :

if this issue comes like : Jwt Authentication error Argument 3 passed to Lcobucci\JWT\Signer\Hmac::doVerify()

Do the following : 

(a) php artisan key:generate ; 
(b) php artisan jwt:secret ; 
(c) php artisan cache:clear ; 
(d) php artisan config:clear ;