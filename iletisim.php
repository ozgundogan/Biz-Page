<?php
include("header.php")?>
<!--/ Section Contact-Footer Star /-->
<section class="paralax-mf footer-paralax bg-image sect-mt4 route" style="background-image: url(img/overlay-bg.jpg)">
  <div class="overlay-mf"></div>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="contact-mf">
          <div id="contact" class="box-shadow-full">
            <div class="row">
              <div class="col-md-6">
                <div class="title-box-2">
                  <h5 class="title-left">
                    Send Message Us
                  </h5>
                </div>
                <div>
                    <form action="islem.php" method="post" role="form" class="contactForm">
                    <div id="sendmessage">Mesajınız gönderildi. Teşekkürler!</div>
                    <div id="errormessage">Mesajınız gönderilemedi!</div>
                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <div class="form-group">
                          <input type="text" name="isim" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                          <div class="validation"></div>
                        </div>
                      </div>
                      <div class="col-md-12 mb-3">
                        <div class="form-group">
                          <input type="email" name="mail" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                          <div class="validation"></div>
                        </div>
                      </div>
                      <div class="col-md-12 mb-3">
                          <div class="form-group">
                            <input type="text" name="konu" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                            <div class="validation"></div>
                          </div>
                      </div>
                      <div class="col-md-12 mb-3">
                        <div class="form-group">
                          <textarea class="form-control" name="mesaj" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
                          <div class="validation"></div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <button name="submit" type="submit" class="button button-a button-big button-rouded">Send Message</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-md-6">
                <div class="title-box-2 pt-4 pt-md-0">
                  <h5 class="title-left">
                    Get in Touch
                  </h5>
                </div>
                <div class="more-info">
                  <p class="lead">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis dolorum dolorem soluta quidem
                    expedita aperiam aliquid at.
                    Totam magni ipsum suscipit amet? Autem nemo esse laboriosam ratione nobis
                    mollitia inventore?
                  </p>
                  <ul class="list-ico">
                    <li><span class="ion-ios-location"></span> 329 WASHINGTON ST BOSTON, MA 02108</li>
                    <li><span class="ion-ios-telephone"></span> (617) 557-0089</li>
                    <li><span class="ion-email"></span> contact@example.com</li>
                  </ul>
                </div>
                <div class="socials">
                  <ul>
                    <li><a href=""><span class="ico-circle"><i class="ion-social-facebook"></i></span></a></li>
                    <li><a href=""><span class="ico-circle"><i class="ion-social-instagram"></i></span></a></li>
                    <li><a href=""><span class="ico-circle"><i class="ion-social-twitter"></i></span></a></li>
                    <li><a href=""><span class="ico-circle"><i class="ion-social-pinterest"></i></span></a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--/ Section Contact-footer End /-->
<?php include("footer.php") ?>
