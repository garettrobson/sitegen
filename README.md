# Sitegen
CLI tool for performing common LAMP tasks.

## Installation

* Download the project.
* Compile a Phar with Box.
* Ensure it is executable (`chmod a+x`)
* Place the Phat in a path accessible location (such as `/usr/local/bin`)

### Running sitegen

When you run sitegen it will need to perform actions to you're system which
likely require root permissions. To keep things safe, and prevent the need for
only complex ACL security systems, it relies on POSIX inbuilt security. As such
you should run the command as root, typically by way of the `sudo` command.
s
However running it as root is not a prerequisite as much as a recommendation.
When you run the command settings are stored in the executing users home
directory, so by running as root you preclude unauthorised users from accessing
these settings and modifying them or reading them. **Since the settings will
include details such as database connection details for a user able to create
databases, add users and modify permissions, *it is paramount these details be
kept safe***.

## Setup

Before you can create project folders, databases and vhosts you need to setup
sitegen for each of these types of interaction. This can either be done by
running each of the setup commands for these types of task, or running the
general setup command which will run all of them in turn.

```
# General setup
sitegen setup

# Task specific setup
sitegen db:setup
sitegen host:setup
sitegen dir:setup
```
