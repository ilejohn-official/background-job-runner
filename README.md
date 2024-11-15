# Custom Background Job Runner

## Table of contents

- [General Info](#general-info)
- [Requirements](#requirements)
- [Setup](#setup)
- [Usage](#usage)
- [Advanced Features](#advanced-features)
- [Assumptions, Limitations, and Future Improvements](#assumptions-limitations-and-future-improvements)

## General Info

This project builds a custom system to execute PHP classes as background jobs, independent of Laravel's built-in queue system.
It queues, executes, and manage background jobs within a Laravel application, independent of Laravel's built-in queue system using redis as a job queue it offers significant improvements in terms of scalability, speed, and reliability. The system supports job priority, delayed execution, retry attempts, and a web-based dashboard for monitoring and managing jobs.

## Requirements

- [php ^8.1](https://www.php.net/ "PHP")

## Setup

- Clone the project and navigate to it's root path and install the required dependency packages using the below commands on the terminal/command line interface.

  ```bash
  git clone https://github.com/ilejohn-official/background-job-runner.git
  cd background-job-runner
  ```

  ```
  composer install
  ```

- Copy and paste the content of the .env.example file into a new file named .env in the same directory as the former and set it's values based on your environment's configuration.

- Generate Application Key

  ```
  php artisan key:generate
  ```
- Run Migration

  ```
  php artisan migrate
  ```

- Ensure the php redis extension is installed and that redis is running as this package uses Redis to store job data and manage queues.
  ```
  sudo apt-get install php-redis
  ```

- **Enable Redis in Laravel** by updating `.env`:
   ```env
   QUEUE_CONNECTION=redis
   REDIS_HOST=127.0.0.1
   REDIS_PORT=6379
   ```

## Usage

  ### To run local server

    ```
    php artisan serve
    ```

  ### To run a class as background job.
   **Job class creation**
    Create a job class with the desired method to run preferrably in the app directory or any custom namespaced class.
    Register the class in the file `config/background_jobs.php` array as a value of the key `approved_classes` so it's pre-approved to run.

   **Helper method usage**
    Use the `runBackgroundJob` helper function to queue the job class. This function requires the following parameters:

    ```
    $class // The class containing the method to execute.
    $method: // The method within the class to call.
    $parameters: // An array of parameters to pass to the method (optional).
    $priority: // An integer representing the job's priority (0 = low, 1 = high).
    $delay: // Time in seconds to delay job execution (optional).
    ```

    You can configure the number of retries by setting `BAKGROUND_JOB_MAX_RETRIES` in .env

   **Example**
   
    ```
    runBackgroundJob(\App\Jobs\SampleJob::class, 'execute', ['param1', 'param2'], 1, 60);
    ```

    In this example:

   - `\App\Jobs\SampleJob::class` is the class containing the job method.
   - `execute` is the method within the job class.
   - `['param1', 'param2']` are parameters passed to the method.
   - `1` is the priority (high).
   - `60` is a delay in seconds before executing.

  ### Test cases
   - execute `php artisan app:run-test-jobs {case}` where case is a,b,c or d which are 4 different scenarios been evaluated. app/Console/Commands/RunTestJob.php has the details of what each case tests for. check the log files
   in storage/logs for the output. Allow for for 10 seconds in betweeen in each run so the loop can reset properly. You can modify for more variation.

## Advanced Features

 ### Web-Based Dashboard
   - A web-based dashboard is available for managing and monitoring jobs. Register first then login before accessing
   the routes. The dashboard provides the following features:

  #### 1. **Job Listing**
  View active background jobs with status, retry count, and priority.

  ##### `/background-jobs`

  ### 2.  **Job Logs**
  Access logs for each job.

  ##### `/background-jobs/{id}/logs`

  ### 3.  **Job Cancellation**
  Cancel any running job, preventing further processing.

## Assumptions, Limitations, and Future Improvements
 ### Assumptions
 - Redis is available as the primary storage for job data.
 - Jobs are well-defined in Laravel classes, with accessible methods.

 ### Limitations
 - Cancellation: Canceling a job only prevents re-queuing or further processing; it does not stop jobs mid-execution.

 ### Future Improvements
  - Job Dependencies: Allow jobs to depend on other jobs for execution order.
  - Job Scheduling: Integrate scheduling for periodic jobs.
  - Job Pause/Resume: Add support to pause and resume jobs on the dashboard.