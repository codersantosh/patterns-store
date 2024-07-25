# ATOMIC WP CUSTOM TABLE AND QUERY

> WordPress utility classes for streamlined custom table creation, database querying, and CRUD (Create, Read, Update, Delete) operations.

## Description

The ATOMIC WP CUSTOM TABLE AND QUERY project provides two classes: ATOMIC_WP_CUSTOM_TABLE and ATOMIC_WP_CUSTOM_QUERY. These classes simplify the interaction with custom database tables, enabling developers to perform CRUD operations effectively within WordPress products or projects.

## Table of contents

- [ATOMIC WP CUSTOM TABLE AND QUERY](#atomic-wp-custom-table-and-query)
  - [Description](#description)
  - [Table of contents](#table-of-contents)
  - [Getting Started](#getting-started)
  - [Installation](#installation)
  - [Usage](#usage)
  - [Features](#features)
  - [Contributing](#contributing)
  - [Authors](#authors)
  - [Resources](#resources)
  - [License & Attribution](#license--attribution)

## Getting Started

Follow these instructions to utilize the ATOMIC WP CUSTOM TABLE AND QUERY classes:

## Installation

1. Download this repository.
2. Extract and place the folder in your project.
3. Include index.php of this project: Example: require_once plugin/theme root path/path-to-this-repo-folder/. 'index.php';

## Usage

Use the ATOMIC_WP_CUSTOM_TABLE AND QUERY classes for:

- Creating and managing custom database tables.
- Executing custom database queries.
- Performing CRUD operations (Create, Read, Update, Delete) on database records.

## Features

- **ATOMIC_WP_CUSTOM_TABLE Class:** simplifies custom table creation, management, and CRUD operations.
- **ATOMIC_WP_CUSTOM_QUERY Class:** simplifies custom database querying.
- **Security Handling:** Sanitization and validation are taken care of..
- **Cache Handling:** Caching is handled efficiently using cache group, cache key, wp_cache_get and wp_cache_add.
- **Error Handling:** Errors are managed using try-catch blocks. Custom error messages are returned using WP_Error.
- **Efficient Database Interaction:** Streamlines database interactions for improved performance.
- **Flexible Query Building:** Supports complex query construction tailored to specific requirements.
- **Code Organization:** Enhances code organization by encapsulating database-related logic into reusable classes.
- **Easy Integration:** Seamlessly integrates with WordPress plugins or themes for streamlined development.

## Contributing

Thank you for your interest in contributing to Project Coming Soon and Maintenance Mode Page. To submit your changes, please follow the steps outlined below.

1. **Fork the Repository:** Click on the "Fork" button on the top right corner of the repository page to create your fork.

2. **Clone your Fork:** Clone your forked repository to your local machine using the following command:

   ```sh
   git clone https://github.com/your-username/atomic-wp-custom-table-and-query
   ```

3. **Create a Feature Branch:** Create a new branch for your feature or bug fix:
   ```sh
   git checkout -b my-new-feature
   ```
4. **Make Changes:** Add your changes to the project. You can use the following command to stage all changes:

   ```sh
   git add .
   ```

5. **Commit Changes:** Commit your changes with a descriptive commit message:

   ```sh
   git commit -a m 'Add some feature'
   ```

6. **Push to your Branch:** Push your changes to the branch you created on your fork:
   ```sh
   git push origin my-new-feature
   ```
7. **Submit a Pull Request:** Go to the Pull Requests tab of the original repository and click on "New Pull Request." Provide a clear title and description for your changes, and submit the pull request.

Thank you for contributing to this project!

## Authors

- **Santosh Kunwar** - [codersantosh](https://twitter.com/codersantosh)

See also the list of [contributors](https://github.com/codersantosh/patterns-store/graphs/contributors) who participated in this project.

## Recommendations

- [WP React Plugin Boilerplate](https://github.com/codersantosh/wp-react-plugin-boilerplate)
- [Atrc (atrc) - Atomic React Components](https://www.npmjs.com/package/atrc)

## License & Attribution

- GPLv2 or later © [Santosh Kunwar](https://twitter.com/codersantosh).

This library is primarily finished and can be safely integrated into your project. While a few methods remain under development (marked as TODO), the core functionality is stable. I welcome contributions and suggestions from experienced developers!