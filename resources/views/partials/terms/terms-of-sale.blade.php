
<h4 class="text-center sbold"><u>STAFFING DENTAL TERMS OF SALE AGREEMENT</u></h4>

<?php 
	$user_id = Auth::User()->id; 
	$location = App\UserMeta::get_meta($user_id, 'city');

$address = implode(' ', array(
    @$info->street_address,
    @$info->zip_code,
    states(@$info->state),
    @$info->city)
);

?>

<p class="text-justify text-indent">THIS STAFFING DENTAL TERMS OF SALE AGREEMENT (this “Agreement”), entered into this {{ date('F d, Y') }} between Staffing Dental LLC, a Michigan limited liability company (“Staffing Dental”) and {{ @$info->company_name }} located  at {{ ucwords($address) }} (“User”).</p>

<p class="text-justify text-indent">WHEREAS, Staffing Dental owns and operates and online platform, <a href="//staffingdental.com">www.staffingdetnal.com</a> (the “Website”), that connects dental staff and dental employers seeking temporary or permanent staffing needs.</p>

<p class="text-justify text-indent">WHEREAS, User is a dental employer that seeks to use the services provided by Staffing Dental’s Website to post temporary or permanent job opportunities for a single location operated by User.</p>

<p class="text-justify text-indent">NOW, THEREFORE, in consideration of the premises and the mutual covenants set forth herein, the parties hereto agree as follows:</p>

<p class="text-justify text-indent">1.	<b>BY CLICKING TO ACCEPT OR AGREE TO THIS AGREEMENT WHEN THIS OPTION IS MADE AVAILABLE TO YOU, YOU (AS THE USER) ACCEPT AND AGREE TO BE BOUND AND ABIDE BY THIS AGREEMENT.</b>

<p class="text-justify text-indent">2.	<b>THE STAFFING DENTAL PRIVACY POLICY AND TERMS OF USE ARE HEREBY INCORPORATED INTO THIS AGREEMENT BY REFERENCE.</b></p>

<p class="text-justify text-indent">3.	Unless otherwise set forth herein, this Agreement permits the User to post temporary or permanent job postings on the Website <b>FOR A SINGLE USER LOCATION ONLY</b>. During the term of this Agreement (i.e., the User’s use of the Website) and thereafter for six (6) months following the termination or expiration of this Agreement, the User agrees for itself and on behalf of its representatives, employees, agents, not to directly or indirectly enter into any contract or arrangement of any kind or nature, oral or written, or otherwise directly or indirectly deal with or become involved with Dental Staff (as hereinafter defined) identified, procured or otherwise made available to a single User location in violation of this Section 3. Any temporary or permanent employee or contractor (each referred to herein as “Dental Staff”) identified, procured, or otherwise made available to User for a single User location must remain as an employee or contractor at such single User location. If User desires to transfer, relocate, or otherwise move any Dental Staff to another User location, an additional Website profile must be created for any such additional User location and the User must pay the fees set forth in Section 4 below.</p>

<p class="text-justify text-indent">4.	For unlimited job postings for a thirty (30) day period on the Website <b>FOR A SINGLE USER LOCATION</b>, User agrees to pay Staffing Dental One Hundred Dollars ($100) for the thirty (30) days of such use. The payments will be charged to the User’s credit card on file with the Company upon signing up for the Company’s service and on every thirtieth (30th) day thereafter, unless this Agreement and User’s use of the Website and the Company’s services are terminated by the Company for any reason or no reason at all. Failure to pay in accordance with the terms hereof will terminate User’s profile and access to the Company’s Website and the Company’s service.</p>

<p class="text-justify text-indent">5.	 <b>OTHER THAN CONFIRMING THAT A POTENTIAL DENTIST, DENTAL SPECIALIST, OR DENTAL HYGIENIST’S LICENSE IS ACTIVE AND IN GOOD STANDING WITH THE ISSUING STATE (I.E., A STATE OF THE UNITED STATES OF AMERICA), THE COMPANY DOES NOT GUARANTEE OR REPRESENT OR WARRANT THAT: (A) ANY DENTAL STAFF WILL COMPLY WITH ANY APPLICABLE GOVERNMENTAL LAWS, STATUTES, REGULATIONS, RULES, ORDERS, ORDINANCES AND CODES, (B) ANY DENTAL STAFF WILL PERFORM SERVICES IN A PROFESSIONAL MANNER, (C) ANY DENTAL STAFF WILL HAVE THE FULL RIGHT, POWER, AND AUTHORITY TO ENTER INTO ANY AGREEMENT WITH THE USER, (D) PERFORMANCE OF SERVICES BY DENTAL STAFF FOR THE USER DOES NOT BREACH OR CONFLICT WITH THE TERMS OF ANY OTHER AGREEMENT OR OBLIGATION BY WHICH SUCH DENTAL STAFF IS BOUND, AND (E) INFORMATION AND DATA SUBMITTED BY DENTAL STAFF TO THE USER IS TRUE, ACCURATE AND COMPLETE.</b></p>

<p class="text-justify text-indent">6.	User shall, at its sole expense, be responsible for, provide and pay for all equipment, supplies, and other materials used by Dental Staff in connection with the performance of services for User. User shall also be responsible for all other expenses, costs and amounts incurred in the performance of the services by Dental Staff, including without limitation, all withholding of all domestic and/or foreign income taxes (including, all federal, state, provincial and/or local income taxes), the payment and withholding of social security and other payroll taxes, all workers’ compensation, insurance premiums. Staffing Dental shall not be responsible or obligated to pay for any of the foregoing.</p>

<p class="text-justify text-indent">7.	User shall indemnify, defend and hold Staffing Dental, and the agents, employees, officers, managers, members, directors and shareholders of Staffing Dental, harmless from and against any and all claims, demands and liabilities, judgments, damages, settlements and expenses (including, without limitation, attorneys’ fees) arising out of or related to any breach of the representations, warranties or covenants of User contained in this Agreement, or which may be sustained or incurred as a result of any acts or omissions of User and/or Dental Staff and the agents and employees thereof that result in: (a) death or bodily injury to any person, (b) destruction or damage to any property, (c) any violation of any governmental law, statute, regulation, rule, order, ordinance or code, and/or (d) performance, actions, or omissions of Dental Staff and/or User.  Such indemnification shall survive the termination of this Agreement.<p class="text-justify text-indent">

<p class="text-justify text-indent">8.	<b>THE PARTIES AGREE THAT, ANY AND ALL CLAIMS, CONTROVERSIES OR ACTIONS ARISING OUT OF THE TERMS, PROVISIONS OR SUBJECT MATTER OF THIS AGREEMENT, SHALL AND MUST BE: (A) RESOLVED EXCLUSIVELY BY ARBITRATION IN OAKLAND COUNTY, MICHIGAN, IN ACCORDANCE WITH THE RULES OF THE AMERICAN ARBITRATION ASSOCIATION AND ANY DECISION OF ARBITRATION SHALL BE FINAL AND BINDING UPON THE PARTIES AND MAY BE ENTERED AND ENFORCED AS A FINAL AWARD OR JUDGMENT IN ANY COURT OF COMPETENT JURISDICTION (INCLUDING, WITHOUT LIMITATION, COURTS WITH THE UNITED STATES OF AMERICA AND CANADA), AND (B) FILED NO MORE THAN TWELVE (12) MONTHS AFTER THE DATE OF THE ACTION THAT IS THE SUBJECT OF THE CLAIM, CONTROVERSY OR ACTION. USER UNDERSTANDS THAT, WHILE THE STATUTE OF LIMITATIONS FOR ANY SUCH CLAIMS MAY BE LONGER THAN TWELVE (12) MONTHS, USER AGREES TO BE BOUND BY THE TWELVE (12) MONTH PERIOD OF LIMITATIONS SET FORTH HEREIN, AND CONTRACTOR WAIVES ANY STATUTE OF LIMITATIONS TO THE CONTRARY.</b></p>

<p class="text-justify text-indent">9.	All matters relating to this Agreement and any dispute or claim arising therefrom or related thereto (in each case, including non-contractual disputes or claims), shall be governed by and construed in accordance with the internal laws of the State of Michigan without giving effect to any choice or conflict of law provision or rule (whether of the State of Michigan or any other jurisdiction).</p>

<p class="text-justify text-indent">10.	This Agreement, the Privacy Policy, and the Terms of Use constitute the sole and entire agreement between User and Staffing Dental and supersede all prior and contemporaneous understandings, agreements, representations and warranties, both written and oral.</p>

<p class="text-justify text-indent">11.	No waiver of by Staffing Dental of any term or condition set forth in this Agreement shall be deemed a further or continuing waiver of such term or condition or a waiver of any other term or condition, and any failure of Staffing Dental to assert a right or provision under this Agreement shall not constitute a waiver of such right or provision. If any provision of this Agreement is held by a court or other tribunal of competent jurisdiction to be invalid, illegal or unenforceable for any reason, such provision shall be eliminated or limited to the minimum extent such that the remaining provisions of this Agreement will continue in full force and effect. </p>

