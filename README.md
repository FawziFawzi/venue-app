
# A venue management system

This is a management system for venues


## Installation

1- Clone the repo and cd into it.

```bash
  git clone https://github.com/FawziFawzi/venue-app.git

```


2- Run:
 ```bash
  composer install
```
3- Rename or copy .env.example file to .env 

4- Set your database credentials in your .env file.

5- Run:
```bash
  php artisan migrate --seed
```
## API Reference
###  1- Authentication:

#### Register a new user

```http
  POST /api/register
```

Response:
```http
  "message": "User Registered Successfully!",
    "user": {
        "id": 12,
        "name": "ahmed3",
        "email": "ahmed3@email.com"
    }
```

#### Login a  user

```http
  POST /api/login
```

Response:
```http
  "message": "User Login Successfully!",
    "user": {
        "id": 11,
        "name": "ahmed2",
        "email": "ahmed2@email.com"
    },
    "token": "1|xD5sxDYkPfSK5Yg06eryJYMtPGRkJEeqxqwQ7GP32261c3ab"
```
### 2- Venues:

#### List all venues

```http
  GET /api/venues
```
Response:
```http
  "venues": [
        {
            "id": 2,
            "name": "Boehm-Bechtelar",
            "location": "32766 Concepcion Crossing Suite 753",
            "capacity": 167,
            "user_id": 3
        },{...}
```


#### Add a new venue

```http
  POST /api/venues
```
Response:
```http
  "message": "Venue created successfully",
    "venue": {
        "id": 12,
        "name": "Workspace3",
        "location": "gihan st",
        "capacity": "120",
        "user_id": 3
    }
```

#### Update venue details

```http
  PUT /api/venues/{$id}
```
Response:
```http
  "message": "Venue updated successfully",
    "venue": {
        "id": 2,
        "name": "Foodcourt4",
        "location": "mansourah gamaa st",
        "capacity": "10",
        "user_id": 3
    }
```

#### Delete a venue

```http
  DELETE /api/venues/{$id}
```
Response:
```http
  "message": "Venue deleted successfully"
```




## Running Tests


To run tests, run the following command

```bash
  php artisan test
```
#### Testing classes:


 1- AuthTest.php

    ├── a_user_can_register
    ├── invalid_data_registration
    ├── a_user_can_login
    ├── login_with_invalid_credentials

 2- VenueManagementTest.php

    ├── test_unauthorized_access_to_venues
    ├── test_list_all_venues
    ├── test_can_create_a_venue
    ├── test_invalid_data_to_create_a_venue  
    ├── test_can_update_a_venue  
    ├── test_invalid_validation_to_update_a_venue  
    ├── test_update_non_existing_venue
    ├── test_can_delete_a_venue
