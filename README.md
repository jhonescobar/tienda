# tienda
API para consumo de una tienda

Inicialmente despues de clonar el repositorio se debe ejecutar el comando dentro del directorio tienda.main.api:
`composer install`

Se debe crear una base de datos en MySql llamada `tienda` y luego ejecutar el comando `php artisan migrate`

con esto quedara configurada la api

para validar que la api este funcionando correctamente se puede hacer uso de la ruta `localhost:8000/api/fecha-hora` la cual retornara la fecha actual
![image](https://github.com/jhonescobar/tienda/assets/14262800/b34a329a-4b6f-424b-a97d-81e2ccf9d7d1)

para acceder a todas las rutas de la api iniciamos con:

Register `localhost:8000/api/register`
![image](https://github.com/jhonescobar/tienda/assets/14262800/1751634e-387b-4ab9-aea5-35c5e1a7a4af)

Auth `localhost:8000/api/auth`
![image](https://github.com/jhonescobar/tienda/assets/14262800/40e27da3-4933-49b3-8e12-7b5bf69c57b4)

Crear tienda `localhost:8000/api/store/mi_tienda`
![image](https://github.com/jhonescobar/tienda/assets/14262800/5d2f2781-f3c2-48bc-a94d-94b60e516112)

Ver tienda `localhost:8000/api/store/mi_tienda`
![image](https://github.com/jhonescobar/tienda/assets/14262800/dd4304ff-5b96-4021-bb34-4d2dd57a11bc)

Ver tiendas `localhost:8000/api/stores`
![image](https://github.com/jhonescobar/tienda/assets/14262800/e080302e-d374-4770-92b3-b50d10138f4a)

Eliminar tienda `localhost:8000/api/store/mi_tienda`
![image](https://github.com/jhonescobar/tienda/assets/14262800/a1488a9a-d60a-42d1-bac4-412d9f2d4959)

Crear articulo `localhost:8000/api/item/televisor`
![image](https://github.com/jhonescobar/tienda/assets/14262800/e0b951db-1550-441b-b028-372d8326a60c)

Editar articulo `localhost:8000/api/item/televisor`
![image](https://github.com/jhonescobar/tienda/assets/14262800/62958a17-ae6a-4fa6-9aaa-09481d529867)

Ver articulo `localhost:8000/api/item/televisor` para este endpoint es necesario enviar el token de autenticación que retorna el endpoint Auth
![image](https://github.com/jhonescobar/tienda/assets/14262800/a8f333eb-abc9-45d4-a257-45e0f21e21b9)

Eliminar articulo `localhost:8000/api/item/televisor`
![image](https://github.com/jhonescobar/tienda/assets/14262800/4da185cd-2dd8-4f69-a995-8365276f9e0f)

Ver articulos `localhost:8000/api/items`
![image](https://github.com/jhonescobar/tienda/assets/14262800/cb03f419-9b59-4259-9d5a-63a231946b3d)


dentro del repositorio se encuentra la colección de postman para poder implementar las peticiones de manera mas eficientes.


