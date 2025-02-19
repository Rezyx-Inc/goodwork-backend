
{{-- scrolable popup for terms of use --}}
<div class="popup-overlay" id="terms-popup">
    <div class="popup-content">
        <button class="close-popup" onclick="closeTerms()">Close</button>
        <header>
            <h5>Goodwork Terms of Use</h5>
            <p><strong>Last Revised:</strong> 1/1/2025</p>
        </header>

        <section>
            <p>Welcome to Goodwork! We provide an online platform to help healthcare professionals connect with job
                opportunities offered by healthcare facilities and staffing agencies. By accessing or using the Goodwork
                platform, you agree to the terms outlined in this document.</p>
        </section>

        <section>
            <h4>1. Acceptance of Terms</h4>
            <p>These Terms of Use (the “Terms”) constitute a legally binding agreement between you (“you” or “user”) and
                Goodwork, Inc. (“Goodwork,” “we,” “our,” or “us”). These Terms govern your use of the Goodwork website,
                mobile application, and any associated services or features (collectively, the “Platform”). By creating
                an account or using the Platform, you represent and warrant that you are at least 18 years old and have
                the authority to agree to these Terms.</p>
            <p>If you are using the Platform on behalf of a staffing or recruiting agency, healthcare provider, or any
                third party, your access and use are governed by the terms of the written agreement between Goodwork and
                that entity.</p>
            <p>By accessing the Platform, you agree to comply with these Terms, the Privacy Policy, and any additional
                guidelines applicable to specific features or programs.</p>
            <p><strong>IMPORTANT NOTICE:</strong> THESE TERMS INCLUDE A MANDATORY ARBITRATION PROVISION AND CLASS ACTION
                WAIVER, WHICH AFFECT YOUR RIGHTS TO RESOLVE DISPUTES IN COURT.</p>
        </section>

        <section>
            <h4>2. Account Creation and Responsibilities</h4>
            <h5>2.1 Account Creation</h5>
            <p>To use certain features of the Platform, such as applying for job opportunities, you must create an
                account. When registering, you agree to:</p>
            <ul>
                <li>Provide accurate, current, and complete information.</li>
                <li>Update your information to maintain its accuracy.</li>
                <li>Use your legal name and ensure you are legally eligible to work in the United States.</li>
            </ul>
            <h5>2.2 Account Responsibilities</h5>
            <p>You are responsible for safeguarding your login credentials and for all activity under your account.
                Notify Goodwork immediately if you suspect unauthorized access or security breaches.</p>
        </section>

        <section>
            <h4>3. Use of the Platform</h4>
            <h5>3.1 License</h5>
            <p>Goodwork grants you a limited, non-transferable, non-exclusive license to access and use the Platform
                solely for your personal, non-commercial purposes.</p>
            <h5>3.2 Restrictions</h5>
            <p>You may not:</p>
            <ul>
                <li>Reproduce, sell, or commercially exploit the Platform or its contents.</li>
                <li>Use automated tools (e.g., bots, scrapers) to access the Platform.</li>
                <li>Violate intellectual property rights or use the Platform for unlawful purposes.</li>
            </ul>
            <h5>3.3 Suspension or Termination</h5>
            <p>Goodwork may suspend or terminate your access to the Platform for violations of these Terms or any
                suspected unlawful activity.</p>
        </section>

        <section>
            <h4>4. User Content</h4>
            <p>You retain ownership of any content you submit to the Platform (e.g., your profile or messages). By
                submitting content, you grant Goodwork a royalty-free, worldwide license to use, reproduce, and
                distribute it to provide and improve the Platform. You are responsible for ensuring your content does
                not infringe on third-party rights.</p>
        </section>

        <section>
            <h4>5. Disclaimers and Limitations of Liability</h4>
            <h5>5.1 Disclaimer of Warranties</h5>
            <p>Goodwork provides the Platform "as is" and "as available" without warranties of any kind. We do not
                guarantee the accuracy, reliability, or availability of the Platform or any job postings.</p>
            <h5>5.2 Limitation of Liability</h5>
            <p>To the maximum extent permitted by law, Goodwork’s liability for damages will not exceed $50. Goodwork is
                not liable for indirect, incidental, or consequential damages.</p>
        </section>

        <section>
            <h4>6. Modification of Terms</h4>
            <p>Goodwork may modify these Terms at any time. Updated Terms will be posted on the Platform and are
                effective upon posting. Continued use of the Platform constitutes acceptance of the updated Terms.</p>
        </section>

        <section>
            <h4>7. Privacy Policy</h4>
            <p>Your use of the Platform is subject to the Goodwork Privacy Policy, which explains how we collect, use,
                and share your personal information.</p>
        </section>

        <section>
            <h4>8. Arbitration and Dispute Resolution</h4>
            <h5>8.1 Arbitration Agreement</h5>
            <p>All disputes arising under or related to these Terms must be resolved through binding arbitration
                conducted by the American Arbitration Association (AAA) in accordance with its rules. Arbitration will
                be conducted individually; class actions and consolidated arbitrations are not permitted.</p>
            <h5>8.2 Opt-Out Option</h5>
            <p>You may opt out of arbitration by notifying Goodwork in writing within 60 days of first agreeing to these
                Terms.</p>
            <h5>8.3 Governing Law</h5>
            <p>These Terms are governed by the laws of the State of California, without regard to conflict of laws
                principles. Any disputes not subject to arbitration must be resolved in the courts of Los Angeles
                County, California.</p>
        </section>

        <section>
            <h4>9. Entire Agreement</h4>
            <p>These Terms, together with the Privacy Policy and any additional guidelines, constitute the entire
                agreement between you and Goodwork. If any provision is found to be unenforceable, the remaining
                provisions will remain in full effect.</p>
            <p>For more information or assistance, please contact Goodwork at:</p>
            <p><strong>Email:</strong> <a href="mailto:info@goodwork.world">info@goodwork.world</a></p>
        </section>

    </div>
</div>



<script>
    // Open Popup
    function openTerms() {
        document.getElementById('terms-popup').style.display = 'block';
    }

    // Close Popup
    function closeTerms() {
        document.getElementById('terms-popup').style.display = 'none';
    }
</script>
