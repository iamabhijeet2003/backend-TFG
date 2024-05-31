# Backend - Tienda Online

Este repositorio contiene el código del backend para una tienda online que es mí TFG(Trabajo de fin de grado) del Grado Superior de DAW(Desarrollo de aplicaciones web). La aplicación está desarrollada utilizando Symfony 6, PHP y varias integraciones de terceros como Stripe y SendGrid.

## Contenido

- [Instalación](#instalación)
- [Configuración](#configuración)
- [Uso](#uso)
- [API Endpoints](#api-endpoints)
- [Autenticación](#autenticación)
- [Pagos](#pagos)
- [Correo Electrónico](#correo-electrónico)
- [Despliegue](#despliegue)
- [Contribuir](#contribuir)
- [Licencia](#licencia)

## Instalación <a id="instalación"></a>

1. Clona el repositorio:
   ```bash
   git clone https://github.com/iamabhijeet2003/backend-TFG.git
   cd backend-TFG
   ```

2. Instala las dependencias:
   ```bash
   composer install
   ```

3. Crea la base de datos:
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

4. Carga los datos iniciales:
   ```bash
   php bin/console doctrine:fixtures:load
   ```

## Configuración

1. Copia el archivo `.env` y configura tus variables de entorno:
   ```bash
   cp .env .env.local
   ```

2. Configura las variables de entorno en `.env.local`:
   ```env
   DATABASE_URL="mysql://user:password@127.0.0.1:3306/newecom"
   MAILER_DSN=sendgrid://KEY@default
   STRIPE_PUBLIC_KEY=tu_clave_publica
   STRIPE_SECRET_KEY=tu_clave_secreta
   JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
   JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
   JWT_PASSPHRASE=tu_frase_de_paso
   ```

## Uso

1. Levanta el servidor de desarrollo:
   ```bash
   symfony server:start -d
   ```
> [!NOTE]
> Necesitas tener instalado Symfony CLI.
2. La API estará disponible en `http://localhost:8000`.

## API Endpoints

Puedes explorar los endpoints disponibles en la API entrando en http://localhost:8000/api.

## Autenticación

La aplicación utiliza autenticación JWT. Para obtener un token de acceso, envía una solicitud `POST` a `/auth` con las credenciales del usuario.

## Pagos

La integración con Stripe permite procesar pagos de manera segura. Consulta la documentación en el controlador `StripeController` para más detalles.

## Correo Electrónico

La integración con SendGrid se utiliza para enviar correos electrónicos en varios eventos como el registro de usuario y confirmación de pedidos.

## Despliegue

La aplicación se puede desplegar utilizando Docker y Platform.sh.
Actualmente esta desplegada en Microsoft Azure VM.

### Docker

1. Construye y levanta los contenedores:
   ```bash
   docker-compose up --build
   ```

2. La aplicación estará disponible en `http://localhost`.

### Platform.sh

Configura y despliega la aplicación en Platform.sh siguiendo las instrucciones en la carpeta `.platform`.

## Contribuir

Las contribuciones son bienvenidas. Por favor, abre un issue o envía un pull request para mejorar este proyecto.

## Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.
```
