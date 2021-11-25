<?php include(realpath($_SERVER['DOCUMENT_ROOT']) . '/template/header.php'); ?>
<?php changeTitle('Privacy Policy - YuuDrive'); ?>
<div class="container" style="margin-top:30px;font-size:11pt;">
    <div class="card">
        <strong class="card-header bg-primary text-white text-center"><i class="fa fa-bullhorn"></i> Privacy Policy</strong>
        <div class="card-body text-muted">
          <blockquote>Your privacy policy must disclose the manner in which your application accesses, uses, stores, and or shares Google user data.</blockquote>
          <p><?= _NAME; ?> operates the <?php echo BASE_DOMAIN; ?> website, which provides the SERVICE.</p>
          <p>This page is used to inform website visitors regarding our policies with the collection, use, and disclosure of Personal Information if anyone decided to use our Service.</p>
          <p>If you choose to use our Service, then you agree to the collection and use of information in relation with this policy. The Personal Information that we collect are used for providing and improving the Service. We will not use or share your information with anyone except as described in this Privacy Policy.</p>
          <p>The terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, which is accessible at 
          <?php echo BASE_DOMAIN; ?>, unless otherwise defined in this Privacy Policy.</p>
          <ul>
            <li>
              <strong>Information Collection and Use</strong>
              <p>For a better experience while using our Service, we may require you to login by google accounts 
              for verification that you want to use our sharing services. 
              The information that we collect will be used to application access only and not shared with others.</p>
              <p><?php echo $app['name'];?> want to :</p>
              <ul>
              	  <li>Create, modify permission folders in your Google Drive</li>
                  <li>Showing, upload, copy, update, and delete files in your Google Drive</li>
                  <li>Create, access, update, and delete native Google documents in your Google Drive</li>
                  <li>Manage files and documents in your Google Drive (e.g., search, organize, and modify permissions and other metadata, such as title)</li>
              </ul><br/>
              <p>We will access your <b>Google Drive</b> accounts for implementings our sharing concepts. 
              We just <i>create folder </i>&amp;<i> modify permission</i>, <i>showing files</i>, <i>delete file</i>, <i>create file</i> &amp; <i>copying file</i> from other user to your account that you want to download to your google drive account, displays the file information that has been copied and we will display profile information such as <i>ID</i>, <i>Name</i> and <i>Email</i>.</p>
              <p>Note : We don't distrubing other files that you haves.</p>
              <p>Our website uses these “cookies” to collection information and to improve our Service. . If you choose to refuse our cookies, you may not be able to use our Service.</p>
            </li>
            <li>
              <strong>Application Access and Uses</strong>
              <p>We provide an platform for "easy sharing" your Google Drive Files. We know, sometime you will shared your files with your friends. But, how if the links are invisible to you someday? Maybe the file will be removed by the owner or the links are missing?
              So, here we are!</p>
              <p>We offer an 3rd party tools that you can use for safety the links and backup the files that your friends shared with you into your account. For example, there are 2 peoples, A and B. Someday, A shared an file for B by google drive links. You can inputted the links into our tools and the tools will be generated an new links that would be a solution for you. Then, with the new link, B can save the files into her/his account automatically when their click download button. See demo here : <?php echo BASE_HOST; ?>/kOjo</p>
              <p>If you interest with our tools, you can use that at homepage and feel free to distribute that with your friends :)</p>
            </li>
            <li>
              <strong>Security</strong>
              <p>We value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable, and we cannot guarantee its absolute security.</p>
            </li>
            <li>
              <strong>Links to Other Sites</strong>
              <p>Our Service may contain links to other sites. If you click on a third-party link, you will be directed to that site. Note that these external sites are not operated by us. Therefore, we strongly advise you to review the Privacy Policy of these websites. We have no control over, and assume no responsibility for the content, privacy policies, or practices of any third- party sites or services.</p>
            </li>
            <li>
              <strong>Changes to This Privacy Policy</strong>
              <p>We may update our Privacy Policy from time to time. Thus, we advise you to review this page periodically for any changes. We will notify you of any changes by posting the new Privacy Policy on this page. These changes are effective immediately, after they are posted on this page.</p>
            </li>
            <li>
              <strong>Contact Us</strong>
              <p>If you have any questions or suggestions about our Privacy Policy, do not hesitate to contact us via email: <b>yuudrive.com@gmail.com</b>.</p>
            </li>
            <li>
              <strong>Google Privacy Policy</strong>
              <p>Readmore for <a href="https://www.google.com/policies/privacy/" target="_blank">Google Privacy Policy</a></p>
            </li>
          </ul>
        </div>
    </div>
</div>
<?php include(realpath($_SERVER['DOCUMENT_ROOT']) . '/template/footer.php'); ?>