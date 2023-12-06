<?php

namespace App;

use DateTime;
use Symfony\Component\DomCrawler\Crawler;

class Utilities
{
    private string $baseUrl = 'https://www.magpiehq.com/developer-challenge/smartphones';

    /**
     * Removes the "Availability:" prefix from the given text.
     *
     * @param string $availabilityText The availability text with the "Availability:" prefix.
     *
     * @return string The cleaned availability text without the prefix.
     */
    public static function cleanAvailabilityText(string $availabilityText): string
    {
        // Remove the "Availability:" prefix
        $cleanedText = trim(str_replace('Availability:', '', $availabilityText));

        return $cleanedText;
    }

    /**
     * Concatenates a base URL and a relative URL to create an absolute URL.
     *
     * This function takes a base URL and a relative URL, removes any leading '../' from the
     * relative URL, and concatenates the two to create an absolute URL. It ensures there
     * is no trailing slash in the base URL and adds a slash between the base and relative URLs.
     *
     * @param string $baseUrl The base URL, e.g., "https://www.example.com".
     * @param string $relativeUrl The relative URL, e.g., "../images/oluwatobi.png".
     *
     * @return string The absolute URL formed by concatenating the base and relative URLs.
     */
    public static function resolveImageUrl(string $baseUrl, string $relativeUrl): string
    {
        // Remove leading '../' from the relative URL if present
        $relativeUrl = preg_replace('/^\.\.\//', '', $relativeUrl);

        // Concatenate the base URL and the relative URL
        return rtrim($baseUrl, '/') . '/' . ltrim($relativeUrl, '/');
    }


    /**
     * Extracts a date from the given shipping text.
     *
     * @param string $shippingText The shipping text that may contain a date.
     *
     * @return string|null The extracted date or null if no date is found.
     */
    public static function extractShippingDate(string $shippingText): ?string
    {
        // Define common date patterns in shipping text
        $datePatterns = [
            '/(\d{4}-\d{2}-\d{2})/',        // Matches YYYY-MM-DD
            '/(\d{1,2} \w+ \d{4})/',       // Matches 1-2 digit day + month name + YYYY
            '/(\d{2} \w+ \d{4})/',         // Matches 2-digit day + month name + YYYY
            '/\b(\w+day \d{1,2}(?:st|nd|rd|th) \w+ \d{4})\b/'  // Matches "day 1-2 digit day (st/nd/rd/th) month name YYYY"
        ];

        // Loop through date patterns and extract the first match
        foreach ($datePatterns as $pattern) {
            if (preg_match($pattern, $shippingText, $matches)) {
                // Convert the matched date to a consistent format (YYYY-MM-DD)
                $dateTime = new DateTime($matches[1]);
                return $dateTime->format('Y-m-d');
            }
        }

        // Return null if no date is found
        return null;
    }

    /**
     * Converts the capacity string to megabytes (MB).
     *
     * This function takes a capacity string, such as "64GB," and converts it to the equivalent
     * capacity in megabytes (MB). It supports common capacity units like "MB" and "GB" and
     * provides default values in case the capacity string or unit is not recognized.
     *
     * @param string $capacity The capacity string to convert, e.g., "64GB."
     *
     * @return int|null The converted capacity in megabytes (MB) or null if the capacity cannot be determined.
     */
    public static function convertCapacityToMB(string $capacity): ?int
    {
        // Define the multipliers for different capacity units
        $unitMultipliers = [
            'MB' => 1,
            'GB' => 1024,
        ];

        // Extract the unit (e.g., "GB") and numeric value from the capacity string
        if (preg_match('/(\d+)(\w+)/', $capacity, $matches)) {
            // The empty comma before $numericValue is a placeholder for the first element (full matched string) that should be ignored.
            list(, $numericValue, $unit) = $matches;

            // ($numericValue ?? 0) - Checks if $numericValue is set and not null. If it is set, it returns its value; otherwise, it defaults to 0.
            // ($unitMultipliers[$unit] ?? 1) - This part checks if $unitMultipliers[$unit] is set and not null. If it is set, it returns its value; otherwise, it defaults to 1
            return ($numericValue ?? 0) * ($unitMultipliers[$unit] ?? 1);
        }

        // Return null if the capacity cannot be determined
        return null;
    }

    /**
     * Determines if the product is available based on the availability text.
     *
     * @param string $availabilityText The availability text.
     *
     * @return bool True if the product is available, false otherwise.
     */
    public static function isAvailable(string $availabilityText): bool
    {
        // Check if the availability text contains the word "In Stock"
        return stripos($availabilityText, 'In Stock') !== false;
    }
}
