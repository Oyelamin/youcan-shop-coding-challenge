# YouCan Coding Challenge: GitHub Leaders Board

The YouCan Coding Challenge involves creating a leaders board for contributors to a GitHub repository using the GitHub API. The challenge includes various tasks to be accomplished by the user.

## Features

1.  **List User Repositories:**

    As a user, you have the ability to list your GitHub repositories.

2.  **Select Repository:**

    Users can select a repository from the list of their repositories.

3.  **Display User Leaderboard:**

    You can view the list of users participating in the selected repository, sorted by the number of PRs (Pull Requests) reviewed.

4.  **Stats Duration:**

    The leaderboard contains statistics for the last month, and the duration of the statistics can be easily customized.
## Postman Documentation
I have published a postman documentation for this task. Kindly click [HERE](https://documenter.getpostman.com/view/23410509/2s9Y5YR2Et) to access it.


## Authentication

Authentication is a crucial aspect of the YouCan Coding Challenge. An authorization token is generated to ensure the security of the OAuth token obtained from GitHub. It's important to set an expiration for the token to enhance security.

## API Endpoints

### Login

This endpoint is used to log in and obtain an authentication token for accessing the GitHub API.

**Request:**

-   Method: POST
-   Endpoint: [http://127.0.0.1:8000/api/auth/login](http://127.0.0.1:8000/api/auth/login)
-   Body: formdata
    -   `oAuthToken`: GitHub Personal Access Token (PAT)

Please ensure that you set your GitHub Personal Access Token (PAT) for authentication.

## Repositories

This section provides information about the endpoint to list repositories. You can use this endpoint to retrieve a list of repositories with specific pagination parameters.

## API Endpoints
### List Repositories

This endpoint allows you to retrieve a list of repositories.

**Request:**

-   Method: GET
-   Endpoint: [http://127.0.0.1:8000/api/repositories?page=1&limit=20](http://127.0.0.1:8000/api/repositories?page=1&limit=20)
-   Authorization: Bearer Token
-   Token: `<Your Bearer Token>`
-   Params:
    -   `page`: 1 (Page number)
    -   `limit`: 20 (Number of items per page)

Replace `<Your Bearer Token>` with your actual bearer token obtained from authentication.

### Get Repository Details

This endpoint allows you to retrieve detailed information about a specific repository.

**Request:**

-   Method: GET
-   Endpoint: [http://127.0.0.1:8000/api/repositories/{repository-name}](http://127.0.0.1:8000/api/repositories/%7Brepository-name%7D)
-   Authorization: Bearer Token
-   Token: `<Your Bearer Token>`

Replace `<Your Bearer Token>` with your actual bearer token obtained from authentication, and replace `{repository-name}` with the name of the repository for which you want to retrieve details.

## Repository Leaderboard

This section provides information about the endpoint to retrieve a leaderboard for a specific repository's contributors. You can use this endpoint to obtain a leaderboard of contributors based on various filters.

### Get Repository Leaderboard

This endpoint allows you to retrieve a leaderboard of contributors for a specific repository.

**Request:**

-   Method: GET
-   Endpoint: [http://127.0.0.1:8000/api/repositories/{repository-name}/leaderboard](http://127.0.0.1:8000/api/repositories/%7Brepository-name%7D/leaderboard)
-   Authorization: Bearer Token
-   Token: `<Your Bearer Token>`
-   Params:
    -   `username`: Oyelamin (Filter by username)
    -   `min_review_count`: 0 (Minimum PR Review count filter)
    -   `min_pr_count`: 1 (Minimum PR count filter)
    -   `start_date`: 2022-01-01 (Start date for filtering, Example: 2022-01-01, Default: Last Month Date)
    -   `end_date`: 2023-12-12 (End date for filtering, Example: 2023-12-12, Default: Last Month Date)
    -   `pr_state`: closed (Options: [closed, open, all], Default: "open")

Replace `<Your Bearer Token>` with your actual bearer token obtained from authentication, and replace `{repository-name}` with the name of the repository for which you want to retrieve details.

## Getting Started

To get started with the YouCan Coding Challenge, follow these steps:

1.  Clone the repository to your local machine.
2.  Set up the necessary environment and dependencies. You can check the [deployment script](https://github.com/Oyelamin/youcan-shop-coding-challenge/blob/main/.github/workflows/app-deployment.yml) for more info...
3. Make sure you run `php artisan jwt:secret` to generate jwt token.
3.  Configure your GitHub Personal Access Token (PAT) for authentication in the provided endpoint.
4.  Implement the tasks outlined in the challenge.

## Contributors

-   [Oyelamin](https://github.com/Oyelamin) - Software Engineer

## Contact

For any inquiries or feedback, please contact [ajalablessing49@gmail.com](mailto:ajalablessing49@gmail.com).
