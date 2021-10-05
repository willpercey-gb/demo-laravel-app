# Phonebook Documentation

## Introduction
This is the `Phonebook API` Repository. This project utilises Laravel 8, Laravel Sail.

This project follows the Repository, CQRS, and Simple Event Messaging patterns.

## Initial Checks
Free ports: Please ensure that you're not running MySQL or any HTTP(s) server on your host machine. For example, if you already have MySQL or Apache running on your host machine you'll need to stop those services so the ports become available.

Please shutdown any running docker containers.

## Setup: Prerequisites
Please have docker installed and configured.
Install [Docker for Desktop](https://www.docker.com/products/docker-desktop) tool, for your OS.

- Open command prompt / shell to the location where you cloned the repository
- Type `sail up` and it will start provisioning the envrionment
    - If this is the first time you're doing this it may take a while to download all required images from the internet and build these images on your machine.
    - Later runs will always be quicker.
- Type `sail run bash` and you will be taken to your docker container
- Run `php artisan migrate && php artisan db:seed` to get your database set up
- There are no SSL certificates to import this app runs locally on http


## OpenApi
This api is documented in OpenApi, all API Requests are validated against this schema.

Schema validation must pass before a request can continue.

The OpenApi schema can be found in the yaml file located at `openapi/phonebook-api.yaml`

## Postman
**To setup your API in Postman, first click "Import"**
![](https://i.imgur.com/wUBOHHu.png)

**Select the "File" option and find the `openapi/phonebook-api.yaml` file in the project directory**
![](https://i.imgur.com/uAUH3lM.png)

**Import and navigate back to collecitons.
You should now have a Phonebook API Colleciton and Postman Test Suite**
![](https://i.imgur.com/ILiB4SJ.png)

**Click on the colleciton name and add the following pre-request script**
![](https://i.imgur.com/7sL1YQu.png)
```
const accessTokenReq = {
    url: `${pm.collectionVariables.get('baseUrl')}/v1/auth/login`,
    method: 'POST',
    header: 'Content-Type: application/json',
    body: {
      mode: 'application/json',
      raw: JSON.stringify({
        email: 'test@example.com',
        password: 'HelloWorld123@'
      })
    }
  };
  let getToken = true;
  if (pm.environment.get('accessTokenExpiry') && pm.environment.get('currentAccessToken') && pm.environment.get('accessTokenExpiry') <= new Date().getTime()) {
    getToken = false;
  }
  if (getToken) {
    pm.sendRequest(accessTokenReq, function (err, res) {
      if (err) {
        console.error(err);
      }
      const {access_token,expires_in} = res.json();
      pm.environment.set('currentAccessToken', access_token);
      let expiryDate = new Date();
      expiryDate.setSeconds(expiryDate.getSeconds() + expires_in);
      pm.environment.set('accessTokenExpiry', expiryDate.getTime());
    });
  }
```
**Finally Switch to the authorisation tab select type Bearer Token and add `{{currentAccessToken}}` as the token value and this will automatically keep your JWT token up to date for ongoing requests**
![](https://i.imgur.com/D9GVlAS.png)


## You can now test the API!
![](https://i.imgur.com/CwhpBpP.png)
