<?php
$pageTitle = "Maintain Web";

ob_start();
?>

<!-- Nav tabs -->
<ul class="nav nav-pills nav-success mb-3" role="tablist">
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link active" data-bs-toggle="tab" href="#task" role="tab">Task</a>
    </li>
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link" data-bs-toggle="tab" href="#report" role="tab">Report</a>
    </li>
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link" data-bs-toggle="tab" href="#testing" role="tab">Testing</a>
    </li>
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link" data-bs-toggle="tab" href="#api" role="tab">API</a>
    </li>
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link" data-bs-toggle="tab" href="#package" role="tab">Package</a>
    </li>
</ul>

<div class="card mt-4">
    <div class="card-body">
        <!-- Tab panes -->
        <div class="tab-content text-muted ">
            <div class="tab-pane active" id="task" role="tabpanel">
                <div class="accordion" id="task-accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#daily-task" aria-expanded="true" aria-controls="collapseOne">
                                Daily Tasks
                            </button>
                        </h2>
                        <div id="daily-task" class="accordion-collapse collapse show" aria-labelledby="daily-task" data-bs-parent="#task-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Check Front End
                                    </b>: Ensure the website displays properly and is functional.</li>
                                    <b>
                                        <li>Moderate Comments
                                    </b>: Approve, reply, or delete comments as needed.</li>
                                    <b>
                                        <li>Check Analytics
                                    </b>: Review Google Analytics for visitor insights.</li>
                                    <b>
                                        <li>Continuous Monitoring
                                    </b>: Use an uptime monitoring service (such as UptimeRobot, Pingdom, or Jetpack for WordPress) to check your site’s availability every 1-5 minutes. These services alert you immediately if your site goes down.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#weekly-task" aria-expanded="false" aria-controls="collapseTwo">
                                Weekly Tasks (Choose a Set Day, e.g., Every Monday)
                            </button>
                        </h2>
                        <div id="weekly-task" class="accordion-collapse collapse" aria-labelledby="weekly-task" data-bs-parent="#task-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Backups
                                    </b>: Ensure the site is backed up, ideally daily or at least weekly.</li>
                                    <b>
                                        <li>Update Plugins/Themes/Core
                                    </b>: Keep all components updated for security.</li>
                                    <b>
                                        <li>Security Checks
                                    </b>: Confirm no suspicious activity in logs or files.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#monthly-task" aria-expanded="false" aria-controls="collapseThree">
                                Monthly Tasks (e.g., First Monday of Every Month)
                            </button>
                        </h2>
                        <div id="monthly-task" class="accordion-collapse collapse" aria-labelledby="monthly-task" data-bs-parent="#task-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Check for Broken Links
                                    </b>: Use a plugin like Broken Link Checker.</li>
                                    <b>
                                        <li>Test Forms
                                    </b>: Manually test forms to ensure submissions are working.</li>
                                    <b>
                                        <li>Check Search Console
                                    </b>: Review performance, coverage, and experience issues.</li>
                                    <b>
                                        <li>Test Speed
                                    </b>: Aim for load times under 3 seconds; use tools like Google Lighthouse.</li>
                                    <b>
                                        <li>Clear Unused Media/Files
                                    </b>: Remove media and files no longer in use.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#quarterly-task" aria-expanded="false" aria-controls="collapseThree">
                                Quarterly (e.g., First Monday of Every New Quarter)
                            </button>
                        </h2>
                        <div id="quarterly-task" class="accordion-collapse collapse" aria-labelledby="quarterly-task" data-bs-parent="#task-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Audit Plugins
                                    </b>: Disable and delete plugins that are not in use.</li>
                                    <b>
                                        <li>Update Content
                                    </b>: Update any core pages, product descriptions, or blog content.</li>
                                    <b>
                                        <li>Clear Users
                                    </b>: Remove access for users who no longer need it.</li>
                                    <b>
                                        <li>Update Copyright and Disclaimers
                                    </b>: Ensure copyright years and policy pages are up-to-date.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#yearly-task" aria-expanded="false" aria-controls="collapseThree">
                                Yearly Tasks Once a Year, e.g., January
                            </button>
                        </h2>
                        <div id="yearly-task" class="accordion-collapse collapse" aria-labelledby="yearly-task" data-bs-parent="#task-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Renew Hosting & Domain
                                    </b>: Ensure renewals are set up to avoid expiration.</li>
                                    <b>
                                        <li>Deep Plugin Audit
                                    </b>: Re-evaluate all plugins for necessity and security</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#as-needed-task" aria-expanded="false" aria-controls="collapseThree">
                                As-Needed Tasks
                            </button>
                        </h2>
                        <div id="as-needed-task" class="accordion-collapse collapse" aria-labelledby="as-needed-task" data-bs-parent="#task-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Update Content
                                    </b>: Refresh pages and posts as necessary.</li>
                                    <b>
                                        <li>Delete Unused Media/Files
                                    </b>: Clear out old media files to save space.</li>
                                    <b>
                                        <li>Update Disclaimers/Terms
                                    </b>: Reflect any changes in policies.</li>
                                    <b>
                                        <li>Purge cache
                                    </b>: Anytime you make changes to a page, post, product, etc. make sure you clear the cache of that page (or just the whole site) if your site doesn't automatically do it.</li>
                                    <b>
                                        <li>Premium plugins
                                    </b>: The vast WordPress ecosystem offers a great number of premium plugins. These can contribute useful functionality to websites but development agencies are often reluctant to add them into builds where it’s uncertain whether the client is prepared to foot the bill. Bundling such premium plugin subscriptions into a maintenance plan can offer agencies a convenient way to simplify billing and enhances the overall value offered to the client</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="report" role="tabpanel">
                <div class="accordion" id="report-accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#weekly-report" aria-expanded="true" aria-controls="collapseOne">
                                Weekly Reporting
                            </button>
                        </h2>
                        <div id="weekly-report" class="accordion-collapse collapse show" aria-labelledby="weekly-report" data-bs-parent="#report-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Site Performance
                                    </b>: Report basic metrics like uptime percentage, average page load times, and any downtime incidents. This helps in quickly spotting any emerging issues.</li>
                                    <b>
                                        <li>Traffic Insights
                                    </b>: Provide a snapshot of Google Analytics data (e.g., number of visitors, page views, top pages) to stay informed on site engagement trends.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#monthly-report" aria-expanded="false" aria-controls="collapseTwo">
                                Monthly Reporting
                            </button>
                        </h2>
                        <div id="monthly-report" class="accordion-collapse collapse" aria-labelledby="monthly-report" data-bs-parent="#report-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Security and Updates
                                    </b>: Summarize updates completed (plugins, themes, core), along with any security issues detected or resolved. Include a summary from security plugins like WordFence or Sucuri.</li>
                                    <b>
                                        <li>SEO and Traffic Metrics
                                    </b>: Record top keywords, referral sources, and popular pages from Google Analytics and Google Search Console.</li>
                                    <b>
                                        <li>Performance
                                    </b>: Include detailed speed test results and observations, such as any performance improvements or challenges.</li>
                                    <b>
                                        <li>Content & Engagement
                                    </b>: Highlight engagement metrics like comments received, forms submitted, or social shares, as well as any updates to key content.</li>
                                    <b>
                                        <li>Plugin and Database Health
                                    </b>: Note any issues with plugins or database size and health, such as any tables that may need optimization.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#quarterly-report" aria-expanded="false" aria-controls="collapseThree">
                                Quarterly Reporting
                            </button>
                        </h2>
                        <div id="quarterly-report" class="accordion-collapse collapse" aria-labelledby="quarterly-report" data-bs-parent="#report-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Trend Analysis
                                    </b>: Compare current metrics with previous quarters to identify trends in traffic, performance, or security.</li>
                                    <b>
                                        <li>Content Review
                                    </b>: Summarize updates to key content and assess how these changes impacted traffic and engagement.</li>
                                    <b>
                                        <li>Overall Security Summary
                                    </b>: Include a quarterly security review, summarizing resolved vulnerabilities and ongoing issues.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#annual-report" aria-expanded="false" aria-controls="collapseThree">
                                Annual Reporting
                            </button>
                        </h2>
                        <div id="annual-report" class="accordion-collapse collapse" aria-labelledby="annual-report" data-bs-parent="#report-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Comprehensive Review
                                    </b>: Include a year-long summary of uptime, security, traffic growth, performance metrics, and major updates. Highlight key accomplishments, recurring issues, and areas for improvement.</li>
                                    <b>
                                        <li>Annual SEO Summary
                                    </b>: Analyze how the site has performed in search rankings over the year, identifying top content and keywords.</li>
                                    <b>
                                        <li>Hosting and Service Review
                                    </b>: Review the performance and reliability of your hosting provider and other services, and consider any upgrades if needed.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="testing" role="tabpanel">
                <div class="accordion" id="testing-accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#functional-testing" aria-expanded="true" aria-controls="collapseOne">
                                Functional Testing
                            </button>
                        </h2>
                        <div id="functional-testing" class="accordion-collapse collapse show" aria-labelledby="functional-testing" data-bs-parent="#testing-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Core Functionality
                                    </b>: Ensure all essential site functions work (login, search, navigation, etc.).</li>
                                    <b>
                                        <li>Form Testing
                                    </b>: Test all forms (contact, subscription, comment forms) for successful submission and confirmation messages.</li>
                                    <b>
                                        <li>Broken Links
                                    </b>: Scan the site for broken links and resolve them.</li>
                                    <b>
                                        <li>E-commerce Testing (if applicable)
                                    </b>: Check product pages, add-to-cart functionality, checkout process, payment gateways, and order confirmation emails.</li>
                                    <b>
                                        <li>User Account Management
                                    </b>: Test the registration, login, password reset, and profile update processes.</li>
                                    <b>
                                        <li>Admin Interface
                                    </b>: Confirm that all admin panels work correctly, including adding/editing pages, posts, and products.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#compatibility-testing" aria-expanded="false" aria-controls="collapseTwo">
                                Compatibility Testing
                            </button>
                        </h2>
                        <div id="compatibility-testing" class="accordion-collapse collapse" aria-labelledby="compatibility-testing" data-bs-parent="#testing-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Browser Compatibility
                                    </b>: Test the site on popular browsers (Chrome, Firefox, Safari, Edge) and ensure it displays consistently.</li>
                                    <b>
                                        <li>Device Responsiveness
                                    </b>: Verify responsiveness on different devices (desktop, tablet, mobile) and screen sizes.</li>
                                    <b>
                                        <li>Operating Systems
                                    </b>: Test on multiple operating systems, such as Windows, macOS, iOS, and Android.</li>
                                    <b>
                                        <li>Accessibility
                                    </b>: Check accessibility (e.g., color contrast, ARIA labels, screen reader compatibility) to meet WCAG standards.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#performance-testing" aria-expanded="false" aria-controls="collapseThree">
                                Performance Testing
                            </button>
                        </h2>
                        <div id="performance-testing" class="accordion-collapse collapse" aria-labelledby="performance-testing" data-bs-parent="#testing-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Page Load Speed
                                    </b>: Use tools like Google Lighthouse, GTmetrix, and Pingdom to test and record page load times for key pages.</li>
                                    <b>
                                        <li>Mobile Performance
                                    </b>: Check site performance on mobile devices, especially under slower network conditions (3G/4G).</li>
                                    <b>
                                        <li>Database Optimization
                                    </b>: Run database optimization plugins or commands to reduce load time.</li>
                                    <b>
                                        <li>Image Optimization
                                    </b>: Confirm that all images are optimized for web (compressed, resized, and using correct formats like WebP).</li>
                                    <b>
                                        <li>Caching
                                    </b>: Test the impact of caching plugins (like WP Rocket) on speed and ensure cache purges correctly after updates.</li>
                                    <b>
                                        <li>CDN Testing
                                    </b>: If using a CDN, confirm proper delivery of static assets and fast load times across different regions.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#security-testing" aria-expanded="false" aria-controls="collapseThree">
                                Security Testing
                            </button>
                        </h2>
                        <div id="security-testing" class="accordion-collapse collapse" aria-labelledby="security-testing" data-bs-parent="#testing-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>SSL Certificate
                                    </b>: Verify that SSL is configured properly and that the site is fully accessible over HTTPS.</li>
                                    <b>
                                        <li>Vulnerability Scanning
                                    </b>: Use tools like WordFence or Sucuri to scan for vulnerabilities, malware, or other threats.</li>
                                    <b>
                                        <li>Permissions
                                    </b>: Confirm appropriate file permissions on the server to prevent unauthorized access.</li>
                                    <b>
                                        <li>Firewall Testing
                                    </b>: Check that firewall rules are in place and are not overly restrictive, allowing legitimate traffic.</li>
                                    <b>
                                        <li>User Roles and Access Control
                                    </b>: Ensure that each user role has correct access rights and permissions.</li>
                                    <b>
                                        <li>Login Security
                                    </b>: Test brute-force protection, two-factor authentication (if available), and secure password policies.</li>
                                    <b>
                                        <li>Database Security
                                    </b>: Check for SQL injection vulnerabilities and ensure secure database access credentials.</li>
                                    <b>
                                        <li>Review security logs
                                    </b>: Periodically check security logs to identify and address potential security issues, such as unauthorized access attempts or exploitation of vulnerabilities.</li>
                                    <b>
                                        <li>Link monitoring
                                    </b>: Continuously monitor for broken or malicious links to maintain SEO and protect users from potential security hazards.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#seo-testing" aria-expanded="false" aria-controls="collapseThree">
                                SEO Testing
                            </button>
                        </h2>
                        <div id="seo-testing" class="accordion-collapse collapse" aria-labelledby="seo-testing" data-bs-parent="#testing-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>On-Page SEO
                                    </b>: Verify meta titles, descriptions, header tags, and keyword presence on main pages.
                                    <b>
                                        <li>Schema Markup
                                    </b>: Ensure that schema markup is correctly applied to enhance search engine visibility.</li>
                                    <b>
                                        <li>XML Sitemap
                                    </b>: Test the sitemap for accuracy and ensure it's submitted in Google Search Console.</li>
                                    <b>
                                        <li>Canonical URLs
                                    </b>: Confirm that canonical tags are correctly set up to avoid duplicate content issues.</li>
                                    <b>
                                        <li>Robots.txt
                                    </b>: Ensure the robots.txt file is properly configured and doesn’t block important pages.</li>
                                    <b>
                                        <li>Broken Redirects
                                    </b>: Test redirects (301s and 302s) and fix any broken or incorrect ones.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ux-testing" aria-expanded="false" aria-controls="collapseThree">
                                User Experience (UX) Testing
                            </button>
                        </h2>
                        <div id="ux-testing" class="accordion-collapse collapse" aria-labelledby="ux-testing" data-bs-parent="#testing-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Navigation
                                    </b>: Test that the menu structure and navigation flow are logical and intuitive.</li>
                                    <b>
                                        <li>Search Functionality
                                    </b>: Ensure the search bar returns relevant results quickly.</li>
                                    <b>
                                        <li>Page Layout Consistency
                                    </b>: Verify layout consistency across pages (header, footer, sidebar, typography).</li>
                                    <b>
                                        <li>Content Readability
                                    </b>: Test that fonts, spacing, and content structure are easy to read on all devices.</li>
                                    <b>
                                        <li>Error Handling
                                    </b>: Check for user-friendly error messages on forms and other interactive elements.</li>
                                    <b>
                                        <li>Calls to Action (CTAs)
                                    </b>: Ensure CTAs are easy to find and lead to the correct pages.</li>
                                    <b>
                                        <li>Design optimization
                                    </b>: Optimize website design for user friendliness and conversionsions using tools like user behavior analytics, heatmaps and A/B testing.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#content-testing" aria-expanded="false" aria-controls="collapseThree">
                                Content Testing
                            </button>
                        </h2>
                        <div id="content-testing" class="accordion-collapse collapse" aria-labelledby="content-testing" data-bs-parent="#testing-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Media Files
                                    </b>: Ensure that all images, videos, and downloadable files load and function correctly.</li>
                                    <b>
                                        <li>Content Accuracy
                                    </b>: Confirm that important content (contact info, business hours, product descriptions) is accurate and up-to-date.</li>
                                    <b>
                                        <li>Internal Linking
                                    </b>: Check that internal links lead to relevant pages and enhance navigation.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#analytics-tracking-testing" aria-expanded="false" aria-controls="collapseThree">
                                Analytics and Tracking Testing
                            </button>
                        </h2>
                        <div id="analytics-tracking-testing" class="accordion-collapse collapse" aria-labelledby="analytics-tracking-testing" data-bs-parent="#testing-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Google Analytics
                                    </b>: Confirm that Google Analytics is tracking page views and key events correctly.</li>
                                    <b>
                                        <li>Conversion Tracking
                                    </b>: Test goal tracking and eCommerce conversions (if applicable) in Google Analytics or other tools.</li>
                                    <b>
                                        <li>Heatmaps and Session Recording
                                    </b>: If using tools like Hotjar, ensure that heatmaps and session recordings are tracking accurately.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="api" role="tabpanel">
                <div class="accordion" id="api-accordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accessibility-api" aria-expanded="true" aria-controls="collapseOne">
                                Verify API Accessibility
                            </button>
                        </h2>
                        <div id="accessibility-api" class="accordion-collapse collapse show" aria-labelledby="accessibility-api" data-bs-parent="#api-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Basic Check: Open your browser and go to https
                                    </b>://yourdomain.com/wp-json/wp/v2/. This URL is the root of the WordPress REST API. You should see a JSON response listing available routes and endpoints if the API is accessible.</li>
                                    <b>
                                        <li>Common Endpoints: Try accessing a specific endpoint like https://yourdomain.com/wp-json/wp/v2/posts to fetch recent posts. Other useful endpoints include
                                    </b>:
                                    <ul>
                                        <b>
                                            <li>Posts
                                        </b>: /wp-json/wp/v2/posts</li>
                                        <b>
                                            <li>Pages
                                        </b>: /wp-json/wp/v2/pages</li>
                                        <b>
                                            <li>Users
                                        </b>: /wp-json/wp/v2/users</li>
                                        <b>
                                            <li>Comments
                                        </b>: /wp-json/wp/v2/comments</li>
                                    </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#client-api" aria-expanded="false" aria-controls="collapseTwo">
                                Use an API Client (e.g., Postman)
                            </button>
                        </h2>
                        <div id="client-api" class="accordion-collapse collapse" aria-labelledby="client-api" data-bs-parent="#api-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Install Postman
                                    </b>: Download and install Postman to make API requests more manageable.</li>
                                    <b>
                                        <li>Test Requests: Use Postman to make GET, POST, PUT, and DELETE requests to different endpoints. For example, to list posts, send a GET request to https
                                    </b>://yourdomain.com/wp-json/wp/v2/posts.</li>
                                    <b>
                                        <li>Add Authentication
                                    </b>: For secure endpoints (e.g., creating posts), you’ll need authentication. WordPress supports basic authentication (for development) and OAuth or JWT authentication for production.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#authentication-api" aria-expanded="false" aria-controls="collapseThree">
                                Test Authentication
                            </button>
                        </h2>
                        <div id="authentication-api" class="accordion-collapse collapse" aria-labelledby="authentication-api" data-bs-parent="#api-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Basic Authentication
                                    </b>: For development, add your WordPress username and application password (or API key) to headers in Postman.</li>
                                    <b>
                                        <li>JWT Authentication
                                    </b>: Install a plugin like JWT Authentication for WP REST API. Configure it, then request a token and use it in your API calls.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#response-code-api" aria-expanded="false" aria-controls="collapseThree">
                                Check Response Codes
                            </button>
                        </h2>
                        <div id="response-code-api" class="accordion-collapse collapse" aria-labelledby="response-code-api" data-bs-parent="#api-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>200 OK
                                    </b>: Successful request.</li>
                                    <b>
                                        <li>401 Unauthorized
                                    </b>: Authentication required.</li>
                                    <b>
                                        <li>403 Forbidden
                                    </b>: Access denied.</li>
                                    <b>
                                        <li>404 Not Found
                                    </b>: Endpoint does not exist.</li>
                                    <b>
                                        <li>500 Internal Server Error
                                    </b>: Server-side error.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#specific-actions-api" aria-expanded="false" aria-controls="collapseThree">
                                Test Specific Actions
                            </button>
                        </h2>
                        <div id="specific-actions-api" class="accordion-collapse collapse" aria-labelledby="specific-actions-api" data-bs-parent="#api-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Retrieve Data
                                    </b>: Make GET requests to view posts, pages, users, or custom post types.</li>
                                    <b>
                                        <li>Create/Update Data: Use POST or PUT requests to create or update content. For example, send a POST request to https
                                    </b>://yourdomain.com/wp-json/wp/v2/posts with title, content, and status parameters in the body to create a post.</li>
                                    <b>
                                        <li>Delete Data
                                    </b>: Send a DELETE request to delete posts or pages.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#inspect-response-api" aria-expanded="false" aria-controls="collapseThree">
                                Inspect Response Data
                            </button>
                        </h2>
                        <div id="inspect-response-api" class="accordion-collapse collapse" aria-labelledby="inspect-response-api" data-bs-parent="#api-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Check JSON Structure
                                    </b>: Ensure the response data matches the expected JSON format. Look for fields like id, title, content, etc., in post responses.</li>
                                    <b>
                                        <li>Data Consistency
                                    </b>: Verify that the API returns accurate and updated data (e.g., new posts appear, changes reflect promptly).</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cli-debug-api" aria-expanded="false" aria-controls="collapseThree">
                                Debug with WP-CLI
                            </button>
                        </h2>
                        <div id="cli-debug-api" class="accordion-collapse collapse" aria-labelledby="cli-debug-api" data-bs-parent="#api-accordion">
                            <div class="accordion-body">
                                <ul>
                                    <b>
                                        <li>Use WP-CLI: If issues arise, use WP-CLI commands to inspect API settings and check if the REST API is enabled
                                    </b>:
                                    <ul>
                                        <li>wp rest api list</li>
                                        <li>wp rest api check</li>
                                    </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="package" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-4 pb-2 mt-2">
                            <h4 class="fw-semibold fs-22">Choose the plan that's right for you</h4>
                            <p class="text-muted mb-4 fs-15">Simple pricing. No hidden fees. Advanced features for you business.</p>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="card pricing-box">
                            <div class="card-body p-4 m-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold">Essential Package</h5>
                                        <p class="text-muted mb-0">For Startup</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-light rounded-circle text-primary">
                                            <i class="ri-book-mark-line fs-20"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-4">
                                    <h2><sup><small>$</small></sup>190 <span class="fs-13 text-muted">/Month</span></h2>
                                </div>
                                <hr class="my-4 text-muted">
                                <div>
                                    <ul class="list-unstyled text-muted vstack gap-3">
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Weekly Updates</b>: WordPress core, theme, and plugin updates.
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Basic Security Monitoring</b>: Regular security scans with a basic security plugin (e.g., WordFence).
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Daily Backups</b>: Automated daily backups with restoration support.
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Uptime Monitoring</b>: Continuous monitoring with a weekly review.
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Monthly Report</b>: Simple report covering updates, uptime, and backup status.
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="mt-4">
                                        <button class="btn btn-soft-success w-100 waves-effect waves-light">Get started</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-lg-4">
                        <div class="card pricing-box ribbon-box right">
                            <div class="card-body p-4 m-2">
                                <div class="ribbon-two ribbon-two-danger"><span>Popular</span></div>
                                <div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1 fw-semibold">Professional Package</h5>
                                            <p class="text-muted mb-0">Professional plans</p>
                                        </div>
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-light rounded-circle text-primary">
                                                <i class="ri-medal-line fs-20"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <h2><sup><small>$</small></sup> 290<span class="fs-13 text-muted">/Month</span></h2>
                                    </div>
                                </div>
                                <hr class="my-4 text-muted">
                                <div>
                                    <ul class="list-unstyled vstack gap-3 text-muted">
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    All Essential Package Services
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Enhanced Security</b>: Advanced malware scanning, firewall, and login protection.
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Performance Optimization</b>: Monthly speed tests, caching setup, and database optimization.
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Content Checks and Updates</b>: Monthly broken link checks, front-end review, and minor content updates.
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>SEO and Analytics Overview</b>: Monthly analysis of Google Analytics and Google Search Console metrics.
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Detailed Monthly Report</b>: Includes updates, performance, security status, and basic SEO insights.
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="mt-4">
                                        <button class="btn btn-success w-100 waves-effect waves-light">Get started</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-lg-4">
                        <div class="card pricing-box">
                            <div class="card-body p-4 m-2">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1 fw-semibold">Premium Package</h5>
                                            <p class="text-muted mb-0">Enterprise Businesses</p>
                                        </div>
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-light rounded-circle text-primary">
                                                <i class="ri-stack-line fs-20"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <h2><sup><small>$</small></sup> 390<span class="fs-13 text-muted">/Month</span></h2>
                                    </div>
                                </div>
                                <hr class="my-4 text-muted">
                                <div>
                                    <ul class="list-unstyled vstack gap-3 text-muted">
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    All Professional Package Services
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Priority Support</b>: Fast response times and dedicated support for urgent issues.
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Advanced Security Hardening</b>: Custom security policies, real-time monitoring, and dedicated firewall management.
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Monthly Development Hours</b>: A set number of hours for custom development, updates, or troubleshooting.
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Quarterly SEO Review</b>: In-depth quarterly SEO analysis, keyword tracking, and improvement recommendations.
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Comprehensive Monthly Report</b>: Detailed analysis of uptime, performance, security, SEO, and recommendations for growth.
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="mt-4">
                                        <button class="btn btn-soft-success w-100 waves-effect waves-light">Get started</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
            </div>
        </div>
    </div><!-- end card-body -->
</div>

<?php
$pageContent = ob_get_clean();

include 'layout.php';
