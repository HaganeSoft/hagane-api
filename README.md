# HaganeAPI
Sueños de una versión mejorada de nuestros backends

Se han agregado las clases de loader. La librería precarga todo lo que esté sobre api/Classes, dejando a api/Resources como un router. 

###Resource 
####Loader
Al llamar una clase, si se encuentra directamente sobre la carpeta Classes, se llama a *clase@método*, en caso de estar debajo de más carpetas, se llama tal como su namespace a partir de *\Hagane\Classes\*
``` 
<?php
namespace Hagane\Resource;

use \Hagane\Load\Loader;

class Inventories extends AbstractResource{
	function load() {
    $this->get('/school/:schoolId/products', function() {
			Loader::call('Places\Inventories\Getters\Normal@schoolProducts', [
				'_GET' => $_GET,
				'schoolId' => $this->params['schoolId']
			]);
		});
  }
}
```

###Classes

####Namespace
Recuerda que el namespace debe contener las carpetas en la que se encuentra el archivo

```
<?php
namespace Hagane\Classes\Customers;

use Hagane\Resource\AbstractResource;

class Customers extends AbstractResource{
	public function all() {
		$accessToken = !empty($_GET['accessToken']) ? $_GET['accessToken'] : null;
		$roles = array('administrator', 'supervisor', 'employee');
		if ($this->role($accessToken, $roles)) {
			$customers = $this->db->query('SELECT c.*, s.name as school FROM customer as c join school as s on s.id=c.school_id;');
			$this->message->append('customers', $customers));
		}
		echo $this->message->send();
	}
}
```
