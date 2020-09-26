# ThemePlate Resource

## Usage

```php
use ThemePlate\Resource;

Resource::hint( 'dns-prefetch', '//cdnjs.cloudflare.com' );
Resource::hint( 'preconnect', '//ajax.cloudflare.com' );
Resource::hint( 'prerender', 'http://my.site/blog' );
Resource::hint( 'prefetch', 'jquery-migrate' );
Resource::hint( 'preload', 'jquery-core' );

Resource::hint( 'preload', array(
	'href' => 'https://fonts.gstatic.com/s/montserrat/v14/JTURjIg1_i6t8kCHKm45_cJD3gTD_u50.woff2',
	'as'   => 'font',
	'type' => 'font/woff2',
) );
```

### Resource::hint( $directive, $resource )
- **$directive** *(string)(Required)* Type of directive to use
- **$resource** *(mixed)(Required)*
> - URL *(string)*
>   - `dns-prefetch`
>   - `preconnect`
>   - `prerender`
> - Handle *(string)*
>   - `prefetch`
>   - `preload`
> - Custom *(array)*
>   - `prefetch`
>   - `preload`

---

### Optional to specify the position of tags
#### Resource::init( $priority )
- **$priority** *(integer)(Required)*
