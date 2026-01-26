<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class VisionSphereContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update Settings
        $this->updateSettings();
        
        // Create/Update Pages
        $this->createPages();
    }

    private function updateSettings(): void
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'Vision Sphere', 'type' => 'text', 'group' => 'general', 'label' => 'Site Name'],
            ['key' => 'site_tagline', 'value' => 'Explore Your World of Ideas and Stories', 'type' => 'text', 'group' => 'general', 'label' => 'Site Tagline'],
            ['key' => 'site_description', 'value' => 'Vision Sphere is your premier destination for insightful articles, creative stories, and thought-provoking content. We explore technology, lifestyle, business, health, and more to inspire and inform our readers.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Site Description'],
            ['key' => 'site_logo', 'value' => '', 'type' => 'image', 'group' => 'general', 'label' => 'Site Logo'],
            ['key' => 'site_favicon', 'value' => '', 'type' => 'image', 'group' => 'general', 'label' => 'Favicon'],
            ['key' => 'posts_per_page', 'value' => '10', 'type' => 'number', 'group' => 'general', 'label' => 'Posts Per Page'],
            
            // Contact Settings
            ['key' => 'contact_email', 'value' => 'contact@visionsphere.com', 'type' => 'email', 'group' => 'contact', 'label' => 'Contact Email'],
            ['key' => 'contact_phone', 'value' => '+1 (555) 123-4567', 'type' => 'text', 'group' => 'contact', 'label' => 'Phone Number'],
            ['key' => 'contact_address', 'value' => '123 Innovation Boulevard, Suite 500, San Francisco, CA 94105, USA', 'type' => 'textarea', 'group' => 'contact', 'label' => 'Address'],
            
            // Social Media
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/visionsphere', 'type' => 'url', 'group' => 'social', 'label' => 'Facebook URL'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/visionsphere', 'type' => 'url', 'group' => 'social', 'label' => 'Twitter URL'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/visionsphere', 'type' => 'url', 'group' => 'social', 'label' => 'Instagram URL'],
            ['key' => 'linkedin_url', 'value' => 'https://linkedin.com/company/visionsphere', 'type' => 'url', 'group' => 'social', 'label' => 'LinkedIn URL'],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/@visionsphere', 'type' => 'url', 'group' => 'social', 'label' => 'YouTube URL'],
            
            // Footer
            ['key' => 'footer_text', 'value' => '© ' . date('Y') . ' Vision Sphere. All rights reserved. Explore your world of ideas and stories.', 'type' => 'text', 'group' => 'footer', 'label' => 'Footer Text'],
            ['key' => 'footer_about', 'value' => 'Vision Sphere is dedicated to bringing you quality content that inspires, educates, and entertains. Join our community of curious minds.', 'type' => 'textarea', 'group' => 'footer', 'label' => 'Footer About Text'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    private function createPages(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'meta_title' => 'About Vision Sphere - Our Story and Mission',
                'meta_description' => 'Learn about Vision Sphere, our mission to inspire through quality content, and the team behind our platform.',
                'is_active' => true,
                'content' => $this->getAboutContent(),
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'meta_title' => 'Contact Vision Sphere - Get in Touch',
                'meta_description' => 'Have questions or feedback? Contact the Vision Sphere team. We\'d love to hear from you.',
                'is_active' => true,
                'content' => $this->getContactContent(),
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'meta_title' => 'Privacy Policy - Vision Sphere',
                'meta_description' => 'Read Vision Sphere\'s privacy policy to understand how we collect, use, and protect your personal information.',
                'is_active' => true,
                'content' => $this->getPrivacyPolicyContent(),
            ],
            [
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'meta_title' => 'Terms and Conditions - Vision Sphere',
                'meta_description' => 'Review the terms and conditions for using Vision Sphere\'s website and services.',
                'is_active' => true,
                'content' => $this->getTermsContent(),
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }

    private function getAboutContent(): string
    {
        return <<<HTML
<div class="about-page">
    <section class="hero-section">
        <h1>Welcome to Vision Sphere</h1>
        <p class="lead">Where ideas take flight and stories come alive.</p>
    </section>

    <section class="our-story">
        <h2>Our Story</h2>
        <p>Vision Sphere was born from a simple belief: that great ideas deserve a platform where they can flourish and inspire others. Founded in 2024, we set out to create a space where curious minds could explore, learn, and grow together.</p>
        <p>What started as a small blog has evolved into a thriving community of readers, writers, and thinkers from around the world. Every day, we're humbled by the engagement and passion our audience brings to our content.</p>
    </section>

    <section class="our-mission">
        <h2>Our Mission</h2>
        <p>At Vision Sphere, our mission is to:</p>
        <ul>
            <li><strong>Inspire</strong> - Share stories and ideas that spark creativity and motivation</li>
            <li><strong>Educate</strong> - Provide valuable, well-researched content across diverse topics</li>
            <li><strong>Connect</strong> - Build a community of like-minded individuals who share a thirst for knowledge</li>
            <li><strong>Empower</strong> - Give our readers the insights they need to make informed decisions in their lives</li>
        </ul>
    </section>

    <section class="what-we-cover">
        <h2>What We Cover</h2>
        <p>Our content spans a wide range of topics to satisfy every curious mind:</p>
        <div class="topics-grid">
            <div class="topic">
                <h3>Technology</h3>
                <p>Stay ahead with the latest tech trends, from AI to web development.</p>
            </div>
            <div class="topic">
                <h3>Business</h3>
                <p>Insights on entrepreneurship, leadership, and professional growth.</p>
            </div>
            <div class="topic">
                <h3>Health & Wellness</h3>
                <p>Tips for living a healthier, more balanced life.</p>
            </div>
            <div class="topic">
                <h3>Lifestyle</h3>
                <p>Ideas for making the most of your daily life.</p>
            </div>
            <div class="topic">
                <h3>Travel</h3>
                <p>Explore the world through our travel guides and stories.</p>
            </div>
            <div class="topic">
                <h3>Finance</h3>
                <p>Practical advice for managing and growing your wealth.</p>
            </div>
        </div>
    </section>

    <section class="our-values">
        <h2>Our Values</h2>
        <ul>
            <li><strong>Quality Over Quantity</strong> - We believe in publishing well-researched, thoughtfully written content.</li>
            <li><strong>Authenticity</strong> - We're honest, transparent, and true to our voice.</li>
            <li><strong>Inclusivity</strong> - We welcome diverse perspectives and voices.</li>
            <li><strong>Continuous Improvement</strong> - We're always learning and evolving.</li>
        </ul>
    </section>

    <section class="join-us">
        <h2>Join Our Journey</h2>
        <p>Whether you're here to read, learn, or contribute, we're glad you found us. Vision Sphere is more than a website – it's a community of curious minds exploring the world together.</p>
        <p>Subscribe to our newsletter, follow us on social media, or reach out to say hello. We'd love to hear from you!</p>
    </section>
</div>
HTML;
    }

    private function getContactContent(): string
    {
        return <<<HTML
<div class="contact-page">
    <section class="intro">
        <h1>Get in Touch</h1>
        <p class="lead">We'd love to hear from you! Whether you have a question, feedback, or just want to say hello, don't hesitate to reach out.</p>
    </section>

    <section class="contact-methods">
        <h2>How to Reach Us</h2>
        
        <div class="contact-cards">
            <div class="contact-card">
                <h3>📧 Email Us</h3>
                <p>For general inquiries:</p>
                <p><a href="mailto:contact@visionsphere.com">contact@visionsphere.com</a></p>
                <p>For partnership opportunities:</p>
                <p><a href="mailto:partnerships@visionsphere.com">partnerships@visionsphere.com</a></p>
            </div>

            <div class="contact-card">
                <h3>📍 Visit Us</h3>
                <p>Vision Sphere Headquarters</p>
                <p>123 Innovation Boulevard, Suite 500</p>
                <p>San Francisco, CA 94105</p>
                <p>United States</p>
            </div>

            <div class="contact-card">
                <h3>📱 Call Us</h3>
                <p>Main Line: +1 (555) 123-4567</p>
                <p>Support: +1 (555) 123-4568</p>
                <p>Hours: Mon-Fri 9AM-6PM PST</p>
            </div>
        </div>
    </section>

    <section class="social-connect">
        <h2>Connect on Social Media</h2>
        <p>Stay updated with our latest content and join the conversation on social media:</p>
        <ul class="social-links">
            <li>Facebook: @visionsphere</li>
            <li>Twitter: @visionsphere</li>
            <li>Instagram: @visionsphere</li>
            <li>LinkedIn: Vision Sphere</li>
            <li>YouTube: @visionsphere</li>
        </ul>
    </section>

    <section class="faq">
        <h2>Frequently Asked Questions</h2>
        
        <div class="faq-item">
            <h3>How can I contribute content?</h3>
            <p>We welcome guest contributors! Send us a pitch at <a href="mailto:contribute@visionsphere.com">contribute@visionsphere.com</a> with your topic idea and writing samples.</p>
        </div>

        <div class="faq-item">
            <h3>How do I report an issue with the website?</h3>
            <p>If you encounter any technical issues, please email <a href="mailto:support@visionsphere.com">support@visionsphere.com</a> with details about the problem.</p>
        </div>

        <div class="faq-item">
            <h3>Do you offer advertising opportunities?</h3>
            <p>Yes! For advertising inquiries, please contact <a href="mailto:advertising@visionsphere.com">advertising@visionsphere.com</a>.</p>
        </div>
    </section>

    <section class="response-time">
        <h2>Response Time</h2>
        <p>We aim to respond to all inquiries within 24-48 business hours. For urgent matters, please indicate "URGENT" in your subject line.</p>
    </section>
</div>
HTML;
    }

    private function getPrivacyPolicyContent(): string
    {
        $year = date('Y');
        return <<<HTML
<div class="privacy-policy">
    <p class="last-updated">Last Updated: January 25, {$year}</p>

    <section>
        <h2>1. Introduction</h2>
        <p>Welcome to Vision Sphere ("we," "our," or "us"). We are committed to protecting your privacy and ensuring you have a positive experience on our website. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website visionsphere.com.</p>
        <p>Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy, please do not access the site.</p>
    </section>

    <section>
        <h2>2. Information We Collect</h2>
        
        <h3>Personal Information</h3>
        <p>We may collect personal information that you voluntarily provide to us when you:</p>
        <ul>
            <li>Register on the website</li>
            <li>Subscribe to our newsletter</li>
            <li>Leave comments on articles</li>
            <li>Contact us via email or contact forms</li>
            <li>Participate in surveys or promotions</li>
        </ul>
        <p>This information may include your name, email address, and any other information you choose to provide.</p>

        <h3>Automatically Collected Information</h3>
        <p>When you visit our website, we automatically collect certain information about your device, including:</p>
        <ul>
            <li>IP address</li>
            <li>Browser type</li>
            <li>Operating system</li>
            <li>Access times</li>
            <li>Pages viewed</li>
            <li>Referring website addresses</li>
        </ul>
    </section>

    <section>
        <h2>3. How We Use Your Information</h2>
        <p>We use the information we collect to:</p>
        <ul>
            <li>Provide, maintain, and improve our website</li>
            <li>Send you newsletters and marketing communications (with your consent)</li>
            <li>Respond to your comments, questions, and requests</li>
            <li>Monitor and analyze trends, usage, and activities</li>
            <li>Detect, investigate, and prevent fraudulent transactions and abuse</li>
            <li>Personalize and improve your experience</li>
        </ul>
    </section>

    <section>
        <h2>4. Cookies and Tracking Technologies</h2>
        <p>We use cookies and similar tracking technologies to track activity on our website and hold certain information. Cookies are files with a small amount of data that are sent to your browser from a website and stored on your device.</p>
        <p>You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our website.</p>
    </section>

    <section>
        <h2>5. Third-Party Services</h2>
        <p>We may use third-party services that collect, monitor, and analyze data to improve our service. These third parties have their own privacy policies addressing how they use such information.</p>
        <p>Our website may contain links to other websites. We are not responsible for the privacy practices of these other sites.</p>
    </section>

    <section>
        <h2>6. Data Security</h2>
        <p>We implement appropriate technical and organizational security measures to protect your personal information. However, no method of transmission over the Internet or electronic storage is 100% secure, and we cannot guarantee absolute security.</p>
    </section>

    <section>
        <h2>7. Your Rights</h2>
        <p>Depending on your location, you may have certain rights regarding your personal information, including:</p>
        <ul>
            <li>The right to access your personal information</li>
            <li>The right to correct inaccurate information</li>
            <li>The right to request deletion of your information</li>
            <li>The right to opt-out of marketing communications</li>
            <li>The right to data portability</li>
        </ul>
        <p>To exercise these rights, please contact us at <a href="mailto:privacy@visionsphere.com">privacy@visionsphere.com</a>.</p>
    </section>

    <section>
        <h2>8. Children's Privacy</h2>
        <p>Our website is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you are a parent or guardian and believe your child has provided us with personal information, please contact us.</p>
    </section>

    <section>
        <h2>9. Changes to This Policy</h2>
        <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date.</p>
    </section>

    <section>
        <h2>10. Contact Us</h2>
        <p>If you have any questions about this Privacy Policy, please contact us:</p>
        <ul>
            <li>Email: <a href="mailto:privacy@visionsphere.com">privacy@visionsphere.com</a></li>
            <li>Address: 123 Innovation Boulevard, Suite 500, San Francisco, CA 94105, USA</li>
        </ul>
    </section>
</div>
HTML;
    }

    private function getTermsContent(): string
    {
        $year = date('Y');
        return <<<HTML
<div class="terms-conditions">
    <p class="last-updated">Last Updated: January 25, {$year}</p>

    <section>
        <h2>1. Agreement to Terms</h2>
        <p>By accessing or using Vision Sphere's website (visionsphere.com), you agree to be bound by these Terms and Conditions. If you disagree with any part of these terms, you may not access the website.</p>
    </section>

    <section>
        <h2>2. Intellectual Property Rights</h2>
        <p>The content on Vision Sphere, including but not limited to text, graphics, logos, images, audio clips, and software, is the property of Vision Sphere or its content suppliers and is protected by international copyright laws.</p>
        <p>You may not:</p>
        <ul>
            <li>Reproduce, distribute, or display any content without written permission</li>
            <li>Modify or create derivative works from our content</li>
            <li>Use our content for commercial purposes without authorization</li>
            <li>Remove any copyright or proprietary notices from our content</li>
        </ul>
    </section>

    <section>
        <h2>3. User Accounts</h2>
        <p>When you create an account with us, you must provide accurate, complete, and current information. You are responsible for safeguarding your password and for all activities that occur under your account.</p>
        <p>You agree to:</p>
        <ul>
            <li>Maintain the confidentiality of your account credentials</li>
            <li>Notify us immediately of any unauthorized use</li>
            <li>Accept responsibility for all activities under your account</li>
        </ul>
    </section>

    <section>
        <h2>4. User Content</h2>
        <p>Users may post comments and other content on our website. By posting content, you grant Vision Sphere a non-exclusive, royalty-free, perpetual, and worldwide license to use, reproduce, modify, and display such content.</p>
        <p>You represent and warrant that:</p>
        <ul>
            <li>You own or have the necessary rights to the content you post</li>
            <li>Your content does not violate any third party's rights</li>
            <li>Your content is not defamatory, obscene, or illegal</li>
        </ul>
    </section>

    <section>
        <h2>5. Prohibited Uses</h2>
        <p>You agree not to use the website:</p>
        <ul>
            <li>For any unlawful purpose or to solicit others to perform unlawful acts</li>
            <li>To violate any international, federal, provincial, or state regulations, rules, laws, or local ordinances</li>
            <li>To infringe upon or violate our intellectual property rights or the intellectual property rights of others</li>
            <li>To harass, abuse, insult, harm, defame, slander, disparage, intimidate, or discriminate</li>
            <li>To submit false or misleading information</li>
            <li>To upload or transmit viruses or any other type of malicious code</li>
            <li>To collect or track the personal information of others</li>
            <li>To spam, phish, pharm, pretext, spider, crawl, or scrape</li>
            <li>For any obscene or immoral purpose</li>
            <li>To interfere with or circumvent the security features of the website</li>
        </ul>
    </section>

    <section>
        <h2>6. Disclaimers</h2>
        <p>Vision Sphere provides content for informational purposes only. We make no representations or warranties of any kind, express or implied, regarding:</p>
        <ul>
            <li>The accuracy or completeness of any content</li>
            <li>The reliability of any advice or information obtained through the website</li>
            <li>The availability or uninterrupted operation of the website</li>
        </ul>
        <p>Your use of the website is at your sole risk. The website is provided on an "AS IS" and "AS AVAILABLE" basis.</p>
    </section>

    <section>
        <h2>7. Limitation of Liability</h2>
        <p>In no event shall Vision Sphere, its directors, employees, partners, agents, suppliers, or affiliates be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from:</p>
        <ul>
            <li>Your access to or use of (or inability to access or use) the website</li>
            <li>Any conduct or content of any third party on the website</li>
            <li>Any content obtained from the website</li>
            <li>Unauthorized access, use, or alteration of your transmissions or content</li>
        </ul>
    </section>

    <section>
        <h2>8. Indemnification</h2>
        <p>You agree to defend, indemnify, and hold harmless Vision Sphere and its licensees and licensors, employees, contractors, agents, officers, and directors from any claims, damages, obligations, losses, liabilities, costs, or debt arising from your use of the website or violation of these Terms.</p>
    </section>

    <section>
        <h2>9. Governing Law</h2>
        <p>These Terms shall be governed and construed in accordance with the laws of the State of California, United States, without regard to its conflict of law provisions.</p>
    </section>

    <section>
        <h2>10. Changes to Terms</h2>
        <p>We reserve the right to modify or replace these Terms at any time. We will provide notice of any changes by posting the new Terms on this page. Your continued use of the website after any changes constitutes acceptance of the new Terms.</p>
    </section>

    <section>
        <h2>11. Contact Information</h2>
        <p>If you have any questions about these Terms, please contact us:</p>
        <ul>
            <li>Email: <a href="mailto:legal@visionsphere.com">legal@visionsphere.com</a></li>
            <li>Address: 123 Innovation Boulevard, Suite 500, San Francisco, CA 94105, USA</li>
        </ul>
    </section>
</div>
HTML;
    }
}
