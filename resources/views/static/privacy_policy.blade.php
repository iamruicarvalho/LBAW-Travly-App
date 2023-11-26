@extends('layouts.app')

@section('content')
            <div class="privacy-container">
                <a href="{{ route('home') }}"> < Go back home</a>
                <h2>Privacy Policy</h2>

                <p>At Travly, we are committed to safeguarding the privacy of our users. This Privacy Policy outlines how we collect, use, disclose, and store the information provided by users when they access or use our website.</p>

                <h3>Information Collection</h3>

                <p>When you visit Travly, we may collect personal information such as your name and email address when voluntarily provided by you, for example, when you create an account.</p>

                <h3>Use of Information</h3>

                <p>The information collected is used for the following purposes:
                <ol>
                    <li>To personalize user experience and deliver content and features based on user interests.</li>
                    <li>To improve our website and services based on the feedback we receive.</li>
                </ol>
                </p>

                <h3>Protection of Information</h3>

                <p>We implement a variety of security measures to maintain the safety of your personal information. However, please note that no method of transmission over the internet or electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal information, we cannot guarantee its absolute security.</p>

                <h3>Disclosure of Information</h3>

                <p>We do not sell, trade, or otherwise transfer your personally identifiable information to outside parties. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, as long as those parties agree to keep this information confidential.</p>

                <h3>Third-Party Links</h3>

                <p>Occasionally, at our discretion, we may include or offer third-party products or services on our website. These third-party sites have separate and independent privacy policies. Therefore, we have no responsibility or liability for the content and activities of these linked sites.</p>

                <h3>Consent</h3>

                <p>By using our website, you consent to our Privacy Policy.</p>

                <h3>Changes to Privacy Policy</h3>

                <p>Travly reserves the right to update this Privacy Policy at any time. We encourage users to frequently check this page for any changes to stay informed about how we are helping to protect the personal information we collect.</p>
                <p>If you have any questions regarding this Privacy Policy, you may contact us at contact@travly.com</p> 
                </pre>
            </div>
        </div>
        <link href="{{ url('css/privacy_policy.css') }}" rel="stylesheet">
@endsection
