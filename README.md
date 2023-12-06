# Developer Challenge - Smartphone Scraper

This project is a solution to the developer challenge provided by [Recruiter/Company Name]. The goal of the challenge was to create a web scraper for extracting smartphone product information from a specific website and then store the data in a JSON file.

## Table of Contents

- [Requirements](#requirements)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Dependencies](#dependencies)
- [Installation](#installation)
- [Usage](#usage)

## Requirements

- PHP >= [Your PHP Version]
- Composer (for dependency management)

## Project Structure

The project follows the structure below:
/ # Root directory
|-- src/ # Source code directory
| |-- App/ # Main application files
| |-- Scrape.php # Main scraping class
| |-- Product.php # Product class
| |-- ScrapeHelper.php # Helper class for scraping
| |-- Utilities.php # Utility functions
|-- vendor/ # Composer dependencies
|-- output.json # Output file for scraped data
|-- composer.json # Composer configuration file
|-- README.md # Project documentation

## Dependencies

- [Symfony DomCrawler](https://symfony.com/doc/current/components/dom_crawler.html): Used for web scraping.
- [Guzzle HTTP Client](https://docs.guzzlephp.org/en/stable/): Used for making HTTP requests.

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/Ishoshot/magpie-developer-challenge

   composer install

   ```

## Usage

```bash
php src/Scrape.php

```
