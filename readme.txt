=== Patterns Store - Creates a store for WordPress Patterns ===
Contributors: patternswp, codersantosh
Tags: patterns, pattern kits, templates, gutenberg, blocks
Requires at least: 6.5
Tested up to: 6.6
Requires PHP: 5.6.20
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create a store to manage and display patterns, pattern kits, and theme JSON packages. Perfect for designers and developers.

== Description ==

The Patterns Store plugin is designed for WordPress developers and designers who want to create, manage, and display patterns and pattern kits for their users or clients. This plugin provides an effortless way to handle various design elements within the WordPress platform. It offers an intuitive interface for organizing your patterns and pattern kits, simplifying the management of an extensive collection of design assets.

With Patterns Store, you can manage theme JSON packages globally, by pattern kits, or by individual patterns, offering great flexibility in applying and maintaining your design settings. This makes it ideal for both individual projects and large-scale repositories of patterns. The plugin integrates seamlessly with Gutenberg blocks, allowing you to easily insert and customize patterns on your WordPress site, ensuring that your design elements are both functional and visually appealing.

Whether you are developing custom patterns for specific niche or building a public repository of design elements for a broader audience, Patterns Store provides the tools and flexibility you need to succeed.

We hope you enjoy using Patterns Store and look forward to seeing the amazing designs you create with it. Your feedback and suggestions are always welcome as we strive to improve and expand the capabilities of this plugin.

The plugin is open source and developing on [GitHub](https://github.com/codersantosh/patterns-store/)

== Patterns ==

WordPress patterns are reusable groups of blocks that let you quickly add pre-designed layouts to your pages and posts.

These pre-designed blocks serve as reusable components and can include a variety of elements such as headers, footers, about us sections, accordions, progress bars, service sections, galleries, call-to-actions, and hero sections etc

The purpose of patterns is to simplify the website-building process by providing users with professionally designed and easily customizable elements.

== Pattern kits ==

Pattern kits are collections of similar patterns bundled together. Pattern kits can be considered as a single theme, where a theme's design and corresponding design patterns and theme.json are collectively added to the pattern kits.

These kits act as a set of related or cohesive design patterns that users can apply to their websites collectively.

The idea is to offer users a curated collection of patterns that work well together, providing a consistent and aesthetically pleasing design across various sections and pages of a website.

=== Establishing relation between Pattern Kits and Patterns technically ===

==== Establish Parent-Child Relationship ====

In the context of your Patterns and Pattern Kits setup, creating a parent-child relationship is crucial for organizing your design elements effectively. Follow these steps to establish this hierarchy:

==== Creating a New Pattern Kit ====

By default, any top-level parent post acts as a "Pattern Kit." When you create a new post without selecting a parent, it is considered a top-level parent and functions as a "Pattern Kit."

==== Adding Patterns to a Kit ==== 

Once your "Pattern Kit" is created (or any top-level parent post), you can now add individual "Patterns" to it. When creating or editing a "Pattern," locate the "Parent Pattern Kit" dropdown in the editor.

==== Selecting Parent Pattern Kit ====

In the "Parent Pattern Kit" dropdown, choose the appropriate "Pattern Kit" to associate the current "Pattern" with. This establishes a parent-child relationship between the "Pattern Kit" and the individual "Pattern."

==== Direct Children of Pattern Kits ====

All direct children of "Pattern Kits" are considered "Patterns" within the hierarchy. This default behaviour allows you to easily distinguish between top-level "Pattern Kits" and their associated "Patterns."

==== Visual Representation ====

In the WordPress admin interface, the hierarchical relationship will be visually represented, showing the nested structure of your "Pattern Kits" and their associated "Patterns."

== Theme JSON ==

Theme JSON is a configuration file used in WordPress to define global styles and settings for a theme. It allows you to set typography, color schemes, spacing, and other design elements in a centralized file, making it easier to maintain a consistent look and feel throughout a website.

With Pattern Store you can set up individual theme JSON for Pattern Kits or each Pattern, providing a preview of Patterns that look exactly like the original design as they were designed.

== Features ==

- **Easy Pattern Management:** Effortlessly manage your collection of patterns and pattern kits.
- **Theme JSON package Management:** Manage theme JSON globally for a pattern kit, or individual patterns.

== Installation ==

There are two ways to install the plugin:

1. Upload the plugin's zip file via Dashboard -> Plugins -> Add New -> "Upload Plugin".
2. Extract the plugin folder and place it in the "/wp-content/plugins/" directory.

After installation, activate the plugin through WordPress's 'Plugins' menu.

== Screenshots ==

1. Dashboard - Getting started
2. Dashboard - Settings
3. Dashboard - Settings - General
4. Dashboard - Settings - Advanced


== Changelog ==

= 1.0.0 =
* Initial release