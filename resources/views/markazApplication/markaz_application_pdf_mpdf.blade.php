<!DOCTYPE html>
<html lang="bn" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>মারকায আবেদন ফরম</title>
    <style>
        * {
            font-family: 'solaimanlipi', sans-serif !important;
        }
        body {
            font-family: 'solaimanlipi', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            opacity: 0.05;
            color: #000;
            pointer-events: none;
            z-index: -1;
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #006400;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            margin: 8px 0;
            color: #006400;
            font-weight: bold;
            font-family: 'solaimanlipi', sans-serif !important;
        }

        .header p {
            font-size: 16px;
            margin: 5px 0;
            color: #333;
            font-family: 'solaimanlipi', sans-serif !important;
        }

        .header h2 {
            font-size: 20px;
            margin: 20px 0 10px;
            color: #006400;
            border: 2px solid #006400;
            padding: 8px 15px;
            display: inline-block;
            background-color: #f9f9f9;
            font-weight: bold;
            font-family: 'solaimanlipi', sans-serif !important;
        }

        .form-section {
            margin-bottom: 20px;
            clear: both;
        }

        .form-section label {
            font-weight: bold;
            display: inline-block;
            width: 180px;
            vertical-align: top;
        }

        .form-section span {
            border-bottom: 1px dotted #000;
            padding-bottom: 3px;
            display: inline-block;
            min-width: 220px;
            font-weight: 500;
        }

        .checkbox-group {
            display: inline-block;
            margin-right: 30px;
            position: relative;
        }

        .checkbox-container {
            display: inline-block;
            position: relative;
            padding-left: 25px;
        }

        .checkmark {
            position: absolute;
            top: 2px;
            left: 0;
            height: 16px;
            width: 16px;
            border: 1px solid #000;
            background-color: #fff;
        }

        .checkmark.checked:after {
            content: "✓";
            position: absolute;
            left: 3px;
            top: -2px;
            font-size: 14px;
            color: #006400;
            font-weight: bold;
        }

        .section-heading {
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 20px;
            border-bottom: 2px solid #006400;
            padding-bottom: 8px;
            color: #006400;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }

        table, th, td {
            border: 1px solid #006400;
        }

        th {
            background-color: #e6f2e6;
            color: #006400;
            font-weight: bold;
            padding: 10px 8px;
            text-align: center;
            font-family: 'solaimanlipi', sans-serif !important;
        }

        td {
            padding: 8px 6px;
            text-align: center;
            vertical-align: middle;
            font-family: 'solaimanlipi', sans-serif !important;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer-signature {
            margin-top: 50px;
            text-align: right;
        }

        .footer-signature div {
            border-top: 1px solid #000;
            display: inline-block;
            padding-top: 8px;
            width: 220px;
            text-align: center;
        }

        .attachments {
            margin-top: 25px;
            border: 1px solid #ddd;
            padding: 15px 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .attachments h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #006400;
            font-size: 16px;
        }

        .attachments ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .attachments li {
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }

        .attachments li:before {
            content: '✓';
            color: #006400;
            position: absolute;
            left: 0;
            top: 0;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }

        .conditions-list {
            list-style: none;
            padding: 0;
            counter-reset: item;
        }

        .conditions-list li {
            margin-bottom: 15px;
            padding: 8px 0;
            border-bottom: 1px dotted #ddd;
            position: relative;
            counter-increment: item;
        }

        .conditions-list li p {
            margin: 0;
            padding-left: 30px;
            position: relative;
        }

        .conditions-list li p:before {
            content: counter(item) ".";
            position: absolute;
            left: 0;
            top: 0;
            font-weight: bold;
            color: #006400;
        }

        .sub-conditions {
            list-style: none;
            padding-left: 35px;
            margin-top: 8px;
        }

        .sub-conditions li {
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: none;
        }

        .oath-section {
            margin-top: 35px;
            padding: 20px;
            border: 1px solid #006400;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .oath-title {
            text-align: center;
            margin-top: 0;
            color: #006400;
            border-bottom: 1px solid #006400;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        .signature-line {
            margin-top: 30px;
            display: table;
            width: 100%;
        }

        .signature-line div {
            display: table-cell;
            border-top: 1px solid #000;
            width: 220px;
            padding-top: 8px;
            text-align: center;
        }

        .green-box {
            background-color: #e6f2e6;
            border-left: 4px solid #006400;
            padding: 8px 15px;
            margin-bottom: 20px;
        }

        .address-line {
            margin-bottom: 18px;
        }

        .address-line label {
            width: 180px;
            font-weight: bold;
            display: inline-block;
        }

        .address-line span {
            border-bottom: 1px dotted #000;
            padding-bottom: 3px;
            display: inline-block;
            min-width: 300px;
        }

        .stamp-area {
            border: 1px dashed #006400;
            width: 180px;
            height: 180px;
            margin-top: 25px;
            border-radius: 5px;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            color: #006400;
            font-style: italic;
            padding: 15px;
            opacity: 0.7;
        }

        .highlight-box {
            margin-top: 25px;
            border: 1px solid #006400;
            padding: 15px;
            background-color: #f9f9f9;
        }

        .highlight-box label {
            font-weight: bold;
        }

        .highlight-box span {
            font-weight: bold;
            color: #006400;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="watermark">বেফাক</div>
        
        <div class="header">
            <h1>বেফাকুল মাদারিসিল আরাবিয়া বাংলাদেশ</h1>
            <p>(বাংলাদেশ কওমী মাদরাসা শিক্ষা বোর্ড)</p>
            <p>২০৫, কাজলার পাড় (ভাঙ্গাপ্রেস), যাত্রাবাড়ী, ঢাকা-১২৩৬</p>
            <h2>মারকায আবেদন ফরম (দরসিয়াত)</h2>
        </div>

        <div class="form-section">
            <label>আবেদন নং:</label> <span>{{ $application->application_no ?? 'অফিস পূরণ করবে' }}</span>
        </div>
        
        <div class="form-section">
            <div class="checkbox-group">
                <span class="checkbox-container">ছাত্র
                    <span class="checkmark {{ ($application->is_student ?? false) ? 'checked' : '' }}"></span>
                </span>
            </div>
            <div class="checkbox-group">
                <span class="checkbox-container">ছাত্রী
                    <span class="checkmark {{ ($application->is_female_student ?? false) ? 'checked' : '' }}"></span>
                </span>
            </div>
        </div>

        <div class="green-box">
            <p>বরাবর,</p>
            <p>পরীক্ষা নিয়ন্ত্রক, বেফাকুল মাদারিসিল আরাবিয়া বাংলাদেশ।</p>
        </div>

        <div class="form-section">
            <p>আমি <span>{{ $application->muhtamim_name ?? '' }}</span> মাদরাসার মুহতামিম। মাদরাসাটি <span>{{ $application->years_with_befaq ?? '' }}</span> বছর যাবৎ বেফাকের কেন্দ্রীয় পরীক্ষা দিয়ে আসছে।</p>
            <p>কর্তৃপক্ষের সিদ্ধান্ত অনুসারে বেফাক-এর মারকায (পরীক্ষাকেন্দ্র) মঞ্জুরির আবেদন করছি এবং প্রয়োজনীয় তথ্যসমূহ আবেদনপত্রে উল্লেখ ও সংযুক্ত করছি।</p>
        </div>

        <div class="form-section">
            <div class="address-line">
                <label>ইলহাক নং:</label> <span>{{ $application->ilhok_no ?? '' }}</span>
            </div>
            <div class="address-line">
                <label>মাদরাসা কোড:</label> <span>{{ $application->madrasa_code ?? '' }}</span>
            </div>
            <div class="address-line">
                <label>মাদরাসার নাম:</label> <span>{{ $application->madrasa_name ?? '' }}</span>
            </div>
            <div class="address-line">
                <label>ঠিকানা:</label> <span>{{ $application->address ?? '' }}</span>
            </div>
            <div class="address-line">
                <label>মোবাইল:</label> <span>{{ $application->mobile ?? '' }}</span>
            </div>
            <div class="address-line">
                <label>ই-মেইল:</label> <span>{{ $application->email ?? '' }}</span>
            </div>
            <div class="address-line">
                <label>পূর্বের মারকায:</label> <span>{{ $application->previous_markaz_info ?? '' }}</span>
            </div>
        </div>
        
        <div class="form-section">
            <label>মারকায প্রাপ্তির প্রয়োজনীয়তা:</label>
            <div style="border: 1px solid #ddd; padding: 15px; margin-top: 8px; background-color: #f9f9f9;">
                <span style="border: none; min-width: 100%; display: block;">{{ $application->necessity_for_markaz ?? '' }}</span>
            </div>
        </div>

        <div class="section-heading">
            নিজ মাদরাসাসহ আবেদনকৃত মারকাযে পরীক্ষা দিতে ইচ্ছুক মাদরাসাসমূহের নাম এবং দরসিয়াত পরীক্ষার্থীর সংখ্যা
        </div>

        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 5%;">ক্র.</th>
                    <th rowspan="2" style="width: 25%;">ইলহাক<br>মাদরাসার<br>নাম<br>ও<br>ঠিকানা</th>
                    <th colspan="4">পরীক্ষার্থী</th>
                    <th rowspan="2" style="width: 10%;">মোট<br>পরীক্ষার্থী</th>
                    <th rowspan="2" style="width: 10%;">সম্মতিপত্র</th>
                    <th rowspan="2" style="width: 10%;">অনাপত্তিপত্র</th>
                </tr>
                <tr>
                    <th style="width: 10%;">ফযীলত<br>সানা, উলিয়া</th>
                    <th style="width: 10%;">মুতাওয়াসসিতা</th>
                    <th style="width: 10%;">ইবতিদাইয়া</th>
                    <th style="width: 10%;">সংখ্যা</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($application->affiliated_madrasas) && count($application->affiliated_madrasas) > 0)
                    @foreach($application->affiliated_madrasas as $index => $madrasa)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align: left; padding-left: 8px;">{{ $madrasa->ilhok_madrasa_name_address ?? '' }}</td>
                            <td>{{ $madrasa->fazilot_sana_ulya ?? '' }}</td>
                            <td>{{ $madrasa->mutawassita ?? '' }}</td>
                            <td>{{ $madrasa->ibtidaia ?? '' }}</td>
                            <td>{{ $madrasa->number_of_examinees ?? '' }}</td>
                            <td><strong>{{ $madrasa->total_examinees ?? '' }}</strong></td>
                            <td style="color: {{ $madrasa->consent_letter ? 'green' : 'red' }}">
                                {{ $madrasa->consent_letter ? '✓' : '✗' }}
                            </td>
                            <td style="color: {{ $madrasa->no_objection_letter ? 'green' : 'red' }}">
                                {{ $madrasa->no_objection_letter ? '✓' : '✗' }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9">কোনো সংযুক্ত মাদরাসার তথ্য নেই।</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="highlight-box">
            <label>আবেদনকৃত মারকাযের সর্বমোট পরীক্ষার্থীর সংখ্যা:</label> <span>{{ $application->total_examinees_applied_markaz ?? '' }}</span>
        </div>
        
        <div class="form-section">
            <div class="checkbox-group">
                <span class="checkbox-container">
                    <span class="checkmark {{ ($application->hall_description_checked ?? false) ? 'checked' : '' }}"></span>
                </span>
            </div>
            <label>হলের বিবরণ:</label> স্বতন্ত্র হলরুম মসজিদ। হলের পরিধি- দৈর্ঘ্য <span>{{ $application->hall_length ?? '' }}</span> ফুট, প্রস্থ <span>{{ $application->hall_width ?? '' }}</span> ফুট = মোট <span><strong>{{ $application->total_square_feet ?? '' }}</strong></span> বর্গফুট এবং মানসম্মত <span><strong>{{ $application->tripod_count ?? '' }}</strong></span> টি তেপায়া।
        </div>

        <div class="section-heading">সংযুক্তি</div>
        <div class="attachments">
            <ul>
                <li style="color: {{ ($application->attachment_resolution ?? false) ? 'green' : 'red' }}">
                    ১. মাদরাসা কমিটির মারকায নেওয়ার সিদ্ধান্তের রেজুলেশন
                    @if($application->attachment_resolution ?? false) <span style="color: green;">(সংযুক্ত)</span> @else <span style="color: red;">(সংযুক্ত নয়)</span> @endif
                </li>
                <li style="color: {{ ($application->attachment_previous_markaz_no_objection ?? false) ? 'green' : 'red' }}">
                    ২. পূর্বের মারকাযের সিল-স্বাক্ষরসহ অনাপত্তিপত্র
                    @if($application->attachment_previous_markaz_no_objection ?? false) <span style="color: green;">(সংযুক্ত)</span> @else <span style="color: red;">(সংযুক্ত নয়)</span> @endif
                </li>
                <li style="color: {{ ($application->attachment_befaq_recommendation ?? false) ? 'green' : 'red' }}">
                    ৩. বেফাক জেলা/থানা কমিটির সভাপতি/সেক্রেটারির সুপারিশপত্র
                    @if($application->attachment_befaq_recommendation ?? false) <span style="color: green;">(সংযুক্ত)</span> @else <span style="color: red;">(সংযুক্ত নয়)</span> @endif
                </li>
                <li style="color: {{ ($application->attachment_affiliated_madrasas_consent ?? false) ? 'green' : 'red' }}">
                    ৪. সংযুক্ত মাদরাসাসমূহের সম্মতিপত্র
                    @if($application->attachment_affiliated_madrasas_consent ?? false) <span style="color: green;">(সংযুক্ত)</span> @else <span style="color: red;">(সংযুক্ত নয়)</span> @endif
                </li>
                <li style="color: {{ ($application->attachment_affiliated_madrasas_previous_no_objection ?? false) ? 'green' : 'red' }}">
                    ৫. সংযুক্ত মাদরাসা গত বছর পরীক্ষায় অংশগ্রহণ করে থাকলে পূর্বের মারকাযের সিল-স্বাক্ষরসহ অনাপত্তিপত্র
                    @if($application->attachment_affiliated_madrasas_previous_no_objection ?? false) <span style="color: green;">(সংযুক্ত)</span> @else <span style="color: red;">(সংযুক্ত নয়)</span> @endif
                </li>
            </ul>
        </div>

        <div class="footer-signature">
            <p>মুহতামিমের সিল ও স্বাক্ষর</p>
            <p>মোবাইল নং: <span style="border-bottom: 1px dotted #000;">{{ $application->muhtamim_mobile ?? '' }}</span></p>
            <div class="stamp-area">
                সিলের জন্য<br>নির্ধারিত স্থান
            </div>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="container">
        <div class="watermark">বেফাক</div>
        <div class="header">
            <h1>বেফাকুল মাদারিসিল আরাবিয়া বাংলাদেশ</h1>
            <p>(বাংলাদেশ কওমী মাদরাসা শিক্ষা বোর্ড)</p>
            <h2>মারকায প্রাপ্তির শর্ত ও পালনীয় কর্তব্য (দরসিয়াত)</h2>
        </div>

        <div class="markaz-conditions">
            <ul class="conditions-list">
              <li>
                <p>মারকায আবেদনের জন্য বেফাকের কেন্দ্রীয় পরীক্ষায় কমপক্ষে ৩ বছর অংশগ্রহণ করতে হবে।</p>
              </li>
              <li>
                <p>বেফাক হতে প্রকাশিত পাঠ্যবই পড়াতে হবে এবং বেফাক কর্তৃক নির্ধারিত বার্ষিক চাঁদা ও ফি পরিশোধ থাকতে হবে।</p>
              </li>
              <li>
                <p>মারকায-বিধি অনুসারে পরীক্ষার্থীর সংখ্যা পূর্ণ হতে হবে:</p>
                <ul class="sub-conditions">
                  <li>ক. দরসিয়াত ছাত্র: ঢাকা মহানগরী ২২৫ জন, ঢাকা মফস্বল ও জেলা শহর ১৫০ জন এবং মফস্বল ১২৫ জন।</li>
                  <li>খ. দরসিয়াত ছাত্রী: ঢাকা মহানগরী ১৫০ জন, ঢাকা মফস্বল ও জেলা শহর ১২৫ জন এবং মফস্বল ১০০ জন।</li>
                </ul>
              </li>
              <li>
                <p>আবেদনকৃত মারকায মাদরাসার নিজ পরীক্ষার্থী কমপক্ষে এক-তৃতীয়াংশ হতে হবে।</p>
              </li>
              <li>
                <p>পূর্বের মারকায ও পার্শ্ববর্তী মারকাযের ক্ষতি না হয়; এমন অবস্থানে থাকতে হবে।</p>
              </li>
              <li>
                <p>আবেদনকৃত মারকায মাদরাসার ইলহাক কমপক্ষে সানাবিয়া উলিয়া মারহালা পর্যন্ত থাকতে হবে।</p>
              </li>
              <li>
                <p>আবেদনকৃত মারকায মাদরাসা আবাসিক হতে হবে।</p>
              </li>
              <li>
                <p>পূর্বের মারকাযের সিল-স্বাক্ষরসহ অনাপত্তিপত্র অনলাইনে দাখিল করতে হবে।</p>
              </li>
              <li>
                <p>মাদরাসা কমিটির মারকায নেওয়ার সিদ্ধান্তের রেজুলেশন অনলাইনে দাখিল করতে হবে।</p>
              </li>
              <li>
                <p>বেফাক জেলা বা থানা কমিটির সভাপতি/সেক্রেটারির সুপারিশ অনলাইনে দাখিল করতে হবে।</p>
              </li>
              <li>
                <p>আবেদনকৃত মারকাযে পরীক্ষা দিতে ইচ্ছুক মাদরাসাসমূহের সম্মতিপত্র এবং গত বছর পরীক্ষায় অংশগ্রহণ করে থাকলে পূর্বের মারকাযের সিল-স্বাক্ষরসহ অনাপত্তিপত্র অনলাইনে দাখিল করতে হবে।</p>
              </li>
              <li>
                <p>মারকায-বিধি অনুসারে পরীক্ষার্থীর সংখ্যা অনুপাতে প্রশস্ত পরীক্ষার হল থাকতে হবে:</p>
                <ul class="sub-conditions">
                  <li>ক. প্রতি ৪ (চার) ফুট লম্বা তেপায়ায় ২জন, ৬ (ছয়) ফুট তেপায়ায় ৩জন পরীক্ষার্থী বসবে; এ অনুপাতে পর্যাপ্ত তেপায়ার ব্যবস্থা থাকতে হবে।</li>
                  <li>খ. তেপায়ার দুই সারির মাঝে কমপক্ষে ৩ (তিন) ফুট দূরত্ব থাকতে হবে।</li>
                  <li>গ. পরীক্ষা চলাকালীন পরবর্তী সময়ে হল তালাবদ্ধ থাকতে হবে।</li>
                  <li>ঘ. হলের সম্পূর্ণ কর্তৃত্ব-নেতৃত্ব নেগরানে আলার হাতে অবশ্যই থাকতে হবে।</li>
                  <li>ঙ. হলে পর্যাপ্ত আলো-বাতাস, নিরিবিলি পরিবেশ এবং নেগরানবৃন্দ বসার জন্য (সামনে-পিছনে) উন্মুক্ত জায়গা থাকতে হবে।</li>
                </ul>
              </li>
              <li>
                <p>হলের নিকটবর্তী স্থানে ইস্তিঞ্জাখানা থাকতে হবে।</p>
              </li>
              <li>
                <p>নেগরানদের জন্য সমূদয় চাবিসহ আলাদা কক্ষ ও আলমারির ব্যবস্থা থাকতে হবে।</p>
              </li>
              <li>
                <p>নেগরানদের কক্ষে নেগরান ব্যতীত কারও প্রবেশাধিকার থাকবে না এবং দিবারাত্রিতে কেউ অবস্থান করতে পারবে না।</p>
              </li>
              <li>
                <p>পরীক্ষা সংক্রান্ত কাগজপত্র ও প্রশ্নপত্র প্রিন্টের জন্য কম্পিউটার, প্রিন্টার, ফটোকপি মেশিন, ইন্টারনেট ও বিদ্যুতের ব্যবস্থা অবশ্যই রাখতে হবে। পাশাপাশি বিদ্যুতের বিকল্প ব্যবস্থা অবশ্যই থাকতে হবে। এ সকল ব্যবস্থাপনার ব্যয়ভার মারকায মাদরাসা বহন করবে।</p>
              </li>
              <li>
                <p>বিধি মোতাবেক মারকায কমিটি গঠন করে অর্পিত দায়িত্ব যথাযথভাবে আদায় করা এবং পরীক্ষা চলাকালীন প্রতিদিন মারকাযে উপস্থিত থাকতে হবে।</p>
              </li>
              <li>
                <p>পরীক্ষার্থীর সংখ্যানুপাতে যোগ্য নেগরান-মুমতাহিন প্রদান করতে হবে।</p>
              </li>
              <li>
                <p>বেফাক-এর পরীক্ষা সংক্রান্ত যাবতীয় বিজ্ঞপ্তি/ঘোষণা যথাসময়ে মারকাযের অধীন মাদরাসাসমূহকে অবগত করতে হবে। মাদরাসাগুলোর সাথে সার্বিক যোগাযোগ ও সৌহার্দপূর্ণ আচরণ করতে হবে। প্রয়োজনে পরীক্ষা চলাকালীন তাদের থাকা-খাওয়ার সু-ব্যবস্থা করবে।</p>
              </li>
              <li>
                <p>মারকাযের অনলাইন কার্যক্রম পরিচালনা, মারকাযের অধীন মাদরাসাসমূহের এডমিনদেরকে প্রশিক্ষণ দিয়ে অনলাইন কার্যক্রম সম্পাদনে সহায়তা এবং পরীক্ষা চলাকালীন পূর্ণ সময়ে নেগরানে আলাকে সার্বিক সহযোগিতা করতে, অনলাইন কাজ বুঝে এমন একজন মারকায এডমিন (দায়িত্বশীল)-এর ব্যবস্থা করতে হবে।</p>
              </li>
              <li>
                <p>বিগত ৭/৮/১৮ ঈ. তারিখের ইমতিহান কমিটির বৈঠকের সিদ্ধান্ত মোতাবেক, দরসিয়াত মারকায (ছাত্র/ছাত্রী) মঞ্জুরি-ফি (এককালীন)- ঢাকা মহানগর ২৪০০০/-, ঢাকা মফস্বল ও জেলা শহরগুলোতে ১৫,০০০/- এবং মফস্বল এলাকায় ৯,০০০/- মারকায মঞ্জুর হওয়া-সাপেক্ষে প্রদান করতে হবে।</p>
              </li>
            </ul>
            
            <div class="oath-section">
              <h3 class="oath-title">অঙ্গীকারনামা</h3>
              <p>আমি অঙ্গীকার করছি যে, উল্লিখিত কোনো শর্ত পূরণ না হলে এবং আবেদন ফরমে কোনো অসম্পূর্ণ বা ভুল তথ্য প্রদান করলে; কিংবা নিবন্ধন ও অন্তর্ভুক্তি পরীক্ষার্থী প্রকৃত সংখ্যার চেয়ে বেশি দেখিয়ে থাকলে এবং মারকায-বিধি পালনে কোনোরূপ অসঙ্গতি পরিলক্ষিত হলে, বেফাক কর্তৃপক্ষের যে কোনো ফয়সালা মানতে বাধ্য থাকব।</p>
              <div class="signature-line">
                <div>
                    <p>মুহতামিমের নাম, সিল ও স্বাক্ষর</p>
                </div>
                <div>
                    <p>তাং: <span>{{ $application->oath_date ?? date('d/m/Y') }}</span></p>
                </div>
              </div>
            </div>
          </div>
    </div>
</body>
</html>
