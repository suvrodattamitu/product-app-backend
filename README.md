
running process :
 
composer install
cp .env.example .env

php artisan key:generate
php artisan jwt:secret
php artisan cache:clear
php artisan config:clear

then setup db and finally
php artisan migrate

some issues and solutions :

if this issue comes like : Jwt Authentication error Argument 3 passed to Lcobucci\JWT\Signer\Hmac::doVerify()

Do the following : 

php artisan key:generate
php artisan jwt:secret
php artisan cache:clear
php artisan config:clear