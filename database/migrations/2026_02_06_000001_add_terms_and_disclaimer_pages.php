<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update Terms of Service page with proper content
        $termsContent = $this->getTermsContent();
        $disclaimerContent = $this->getDisclaimerContent();

        // Check if terms-of-service page exists
        $termsPage = DB::table('pages')->where('slug', 'terms-of-service')->first();
        if ($termsPage) {
            DB::table('pages')->where('slug', 'terms-of-service')->update([
                'content' => $termsContent,
                'meta_description' => 'Terms of Service for Vision Sphere. Read our terms and conditions for using our website, blog, and e-commerce services.',
                'updated_at' => now(),
            ]);
        } else {
            DB::table('pages')->insert([
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => $termsContent,
                'meta_description' => 'Terms of Service for Vision Sphere. Read our terms and conditions for using our website, blog, and e-commerce services.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create Disclaimer page
        $disclaimerPage = DB::table('pages')->where('slug', 'disclaimer')->first();
        if (!$disclaimerPage) {
            DB::table('pages')->insert([
                'title' => 'Disclaimer',
                'slug' => 'disclaimer',
                'content' => $disclaimerContent,
                'meta_description' => 'Disclaimer for Vision Sphere. Read our disclaimer about the information provided on our website.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('pages')->where('slug', 'disclaimer')->delete();
    }

    private function getTermsContent(): string
    {
        return <<<'HTML'
<p><strong>Last Updated: February 6, 2026</strong></p>

<h2>1. Acceptance of Terms</h2>
<p>Welcome to Vision Sphere ("Company," "we," "our," or "us"). By accessing or using our website at <a href="https://sphere.vision-erp.com">sphere.vision-erp.com</a> (the "Site"), you agree to be bound by these Terms of Service ("Terms"). If you disagree with any part of these terms, you may not access the Site.</p>

<h2>2. Description of Service</h2>
<p>Vision Sphere provides a platform for publishing blog articles, creative content, e-commerce features through our marketplace, and community interaction. Our services include but are not limited to:</p>
<ul>
    <li>Publishing and reading blog posts and articles across various categories</li>
    <li>Commenting on and interacting with published content</li>
    <li>E-commerce marketplace features including shops, products, and order management</li>
    <li>User account management and personalization</li>
    <li>Newsletter subscriptions and content notifications</li>
</ul>

<h2>3. User Accounts</h2>
<p>When you create an account with us, you must provide accurate, complete, and up-to-date information. You are responsible for:</p>
<ul>
    <li>Maintaining the confidentiality of your account credentials</li>
    <li>All activities that occur under your account</li>
    <li>Notifying us immediately of any unauthorized use of your account</li>
</ul>
<p>We reserve the right to terminate or suspend your account at our sole discretion, without notice, for conduct that we believe violates these Terms or is harmful to other users, us, or third parties.</p>

<h2>4. User-Generated Content</h2>
<p>Our Site allows users to post content, including blog articles, comments, reviews, and shop listings. By posting content, you:</p>
<ul>
    <li>Retain ownership of your original content</li>
    <li>Grant Vision Sphere a non-exclusive, worldwide, royalty-free license to use, reproduce, modify, and display your content in connection with our services</li>
    <li>Warrant that you have the right to post such content and that it does not violate any third-party rights</li>
    <li>Agree not to post content that is unlawful, defamatory, obscene, or otherwise objectionable</li>
</ul>

<h2>5. Intellectual Property</h2>
<p>The Site and its original content (excluding user-generated content), features, and functionality are owned by Vision Sphere and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws. You may not:</p>
<ul>
    <li>Copy, modify, or distribute our content without written permission</li>
    <li>Use our trademarks, logos, or branding without authorization</li>
    <li>Reproduce or mirror any portion of the Site without express consent</li>
</ul>

<h2>6. E-Commerce and Shop Features</h2>
<p>Vision Sphere provides a marketplace platform where users can create shops and sell products. Regarding e-commerce activities:</p>
<ul>
    <li>Shop owners are responsible for the accuracy of their product listings and pricing</li>
    <li>Vision Sphere acts as a platform provider and is not a party to transactions between buyers and sellers</li>
    <li>All purchases are subject to the individual shop's policies regarding returns and refunds</li>
    <li>We reserve the right to remove any shop or product listing that violates our policies</li>
</ul>

<h2>7. Prohibited Activities</h2>
<p>You agree not to engage in any of the following activities:</p>
<ul>
    <li>Using the Site for any unlawful purpose or in violation of any applicable laws</li>
    <li>Posting spam, phishing attempts, or malicious content</li>
    <li>Attempting to gain unauthorized access to our systems or user accounts</li>
    <li>Interfering with or disrupting the integrity or performance of the Site</li>
    <li>Scraping, data mining, or using automated tools to access the Site without permission</li>
    <li>Impersonating another person or entity</li>
</ul>

<h2>8. Third-Party Links</h2>
<p>Our Site may contain links to third-party websites or services that are not owned or controlled by Vision Sphere. We have no control over, and assume no responsibility for, the content, privacy policies, or practices of any third-party sites or services.</p>

<h2>9. Advertising and Monetization</h2>
<p>Vision Sphere may display advertisements on the Site, including but not limited to Google AdSense and other advertising networks. By using our Site, you acknowledge and agree that:</p>
<ul>
    <li>Advertisements may be displayed alongside your content</li>
    <li>We may use cookies and tracking technologies for ad personalization</li>
    <li>We are not responsible for the content of third-party advertisements</li>
</ul>

<h2>10. Limitation of Liability</h2>
<p>In no event shall Vision Sphere, its directors, employees, partners, agents, suppliers, or affiliates be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, data, use, or other intangible losses resulting from:</p>
<ul>
    <li>Your use of or inability to use the Site</li>
    <li>Any unauthorized access to or use of our servers and/or personal information</li>
    <li>Any interruption or cessation of transmission to or from the Site</li>
    <li>Any bugs, viruses, or similar issues transmitted through the Site</li>
</ul>

<h2>11. Indemnification</h2>
<p>You agree to indemnify and hold harmless Vision Sphere and its officers, directors, employees, and agents from any claims, damages, obligations, losses, liabilities, costs, or debt arising from your use of the Site or violation of these Terms.</p>

<h2>12. Governing Law</h2>
<p>These Terms shall be governed by and construed in accordance with the laws applicable in your jurisdiction, without regard to its conflict of law provisions.</p>

<h2>13. Changes to Terms</h2>
<p>We reserve the right to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days' notice prior to any new terms taking effect. Your continued use of the Site after changes constitutes acceptance of the new Terms.</p>

<h2>14. Contact Us</h2>
<p>If you have any questions about these Terms, please contact us:</p>
<ul>
    <li>Email: <a href="mailto:contact@visionsphere.com">contact@visionsphere.com</a></li>
    <li>Through our <a href="/contact">Contact Page</a></li>
</ul>
HTML;
    }

    private function getDisclaimerContent(): string
    {
        return <<<'HTML'
<p><strong>Last Updated: February 6, 2026</strong></p>

<h2>1. General Information</h2>
<p>The information provided on Vision Sphere (<a href="https://sphere.vision-erp.com">sphere.vision-erp.com</a>) is for general informational purposes only. All information on the Site is provided in good faith; however, we make no representation or warranty of any kind, express or implied, regarding the accuracy, adequacy, validity, reliability, availability, or completeness of any information on the Site.</p>

<h2>2. No Professional Advice</h2>
<p>The Site cannot and does not contain professional advice in areas including but not limited to medical, legal, financial, or technical fields. The information is provided for general informational and educational purposes only and is not a substitute for professional advice. Accordingly, before taking any actions based upon such information, we encourage you to consult with the appropriate professionals.</p>

<h2>3. Blog Content Disclaimer</h2>
<p>The views and opinions expressed in blog articles on Vision Sphere are those of the individual authors and do not necessarily reflect the official policy or position of Vision Sphere. Any content provided by our bloggers or authors is of their opinion and is not intended to malign any religion, ethnic group, club, organization, company, individual, or anyone or anything.</p>

<h2>4. Product and Shop Disclaimer</h2>
<p>Vision Sphere provides an e-commerce marketplace platform where third-party sellers can list and sell products. We do not manufacture, store, or ship any products listed on our marketplace. Therefore:</p>
<ul>
    <li>We do not guarantee the quality, safety, or legality of items listed by sellers</li>
    <li>We are not responsible for the accuracy of product descriptions or images provided by sellers</li>
    <li>Any transactions are between the buyer and the seller directly</li>
    <li>We encourage buyers to review seller information and product details carefully before making purchases</li>
</ul>

<h2>5. Affiliate Links and Advertising</h2>
<p>Vision Sphere may contain affiliate links and advertisements. This means we may earn a commission if you click on a link and make a purchase. This comes at no additional cost to you. We only recommend products and services that we believe will add value to our readers. The presence of advertisements, including Google AdSense, does not constitute an endorsement of the advertised products or services.</p>

<h2>6. External Links Disclaimer</h2>
<p>The Site may contain links to external websites that are not provided or maintained by or in any way affiliated with Vision Sphere. Please note that we do not guarantee the accuracy, relevance, timeliness, or completeness of any information on these external websites.</p>

<h2>7. Errors and Omissions</h2>
<p>While we have made every attempt to ensure that the information contained in this site has been obtained from reliable sources, Vision Sphere is not responsible for any errors or omissions or for the results obtained from the use of this information. All information in this site is provided "as is," with no guarantee of completeness, accuracy, timeliness, or of the results obtained from the use of this information.</p>

<h2>8. Fair Use Notice</h2>
<p>This site may contain copyrighted material the use of which has not always been specifically authorized by the copyright owner. We are making such material available for the purposes of criticism, comment, news reporting, teaching, scholarship, or research. We believe this constitutes a "fair use" of any such copyrighted material as provided in applicable copyright law.</p>

<h2>9. User-Generated Content</h2>
<p>Vision Sphere allows users to post comments, reviews, and other content. We do not guarantee the accuracy of user-generated content. Views expressed by users do not represent the views of Vision Sphere. We reserve the right to remove any content that violates our community guidelines.</p>

<h2>10. Changes to This Disclaimer</h2>
<p>We reserve the right to make changes to this Disclaimer at any time. Changes will be effective immediately upon posting on the Site. We encourage visitors to frequently check this page for any changes.</p>

<h2>11. Contact Us</h2>
<p>If you have any concerns or questions about this Disclaimer, please contact us:</p>
<ul>
    <li>Email: <a href="mailto:contact@visionsphere.com">contact@visionsphere.com</a></li>
    <li>Through our <a href="/contact">Contact Page</a></li>
</ul>
HTML;
    }
};
