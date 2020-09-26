# ThemePlate Resource

## Usage

```php
add_action( 'wp_head', array( 'ThemePlate\Preload', 'init' ), 2 );

ThemePlate\Resource::hint( 'dns-prefetch', '//cdnjs.cloudflare.com' );
ThemePlate\Resource::hint( 'preconnect', '//ajax.cloudflare.com' );
ThemePlate\Resource::hint( 'prerender', 'http://my.site/blog' );
ThemePlate\Resource::hint( 'prefetch', 'jquery-migrate' );
ThemePlate\Resource::hint( 'preload', 'jquery-core' );

```
