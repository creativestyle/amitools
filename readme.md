## What is it for?

This set of command lines was created to simplify work with Continous Integration and WAS.
I use it to find newest images to spin off EC2 instances and old images to remove them from the system.

## Usage

Currently ony one command is supported:

`php app/console.php ami:find [--profile PROFILE] <name>`

where name is the value of name tag added to image, and PROFILE is AWS profile name from ~/.aws/credentials file. Defaults to AWS_PROFILE variable value.
This comman will find newest image and return it's name, so it can be used in other scripts.