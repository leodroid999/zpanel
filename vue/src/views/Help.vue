<script>
export default {

}
</script>
<template>
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Z-panel</a></li>
        <li class="breadcrumb-item active">Faq</li>
    </ul>
    <h4 class="page-header">
        Faq<small></small>
    </h4>
    <p>

    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Additional Support</h5>
                    <p class="card-text">
                        If you can't find what you are looking for and still need additional assistance,
                        please contact us through telegram or make a ticket.
                    </p>
                    <div style="float:right;">
                        <a href="https://t.me/tokyosuppor247" target="_blank" class="btn btn-primary">Contact Telegram</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br><br>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    How do I host with Tokyo Panel?
                </button>
            </h2>

            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">

                    <p>
                        Our quick host API will allow you to get things going within 2 minutes.
                        You don't need to do anything complex.<br>
                        You will need two things
                        <ul>
                            <li><b>Website Domain</b> a gemini-59914.com</li>
                            <li>a Linux VPS. we recommend Ubuntu or Debian, they can be cheap
                        you don't need much performance for this a 5-10$ VPS works just
                        fine.
                        (we recommend luxhost.cc / blazingfast.io or a VPS from https://
                        lowendbox.com)s</li>
                        </ul>
                        
                        Let's say your domain is <b>gemini-59914.com</b> and you've an <b>Ubuntu VPS</b>.
                        <br>You still have one step left do.
                        <br>You must change the DNS settings by adding an <b>A records</b> to the domain towards IP of the VPS So find your VPS IP.
                        <br>Let's say your <b>VPS ip</b> was 14.63.244.8 you would change the DNS settings to <br><br>

                        <img src="/assets/img/help_host_01.png" alt="Profile"/><br><br>

                        (Notice it could take some time for the changes to reflect depending on
                        your chosen hosting service)
                        <br>After this step you've hooked the domain to the VPS now you may try
                        hosting.<br>
                        Make sure the Linux VPS is turned on and has no firewall.<br>
                        This is the final step

                        <br><br>
                        <img src="/assets/img/help_host_02.png" alt="Profile"/>
                        <br><br>
                        And press <b>Send</b>, this would automatically host the panel for you.
                        <br>If you have issue's with this you could try the manual install through a SSH
                        client like PuTTY By copying the wget command below
                    </p>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    The hosting didn't work
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    Make sure you have a Linux VPS (Ubuntu, Debian) if it's still not working try
                    reinstalling the VPS to different versions.<br>
                    If it still not working make sure you didn't forget to set the A records
                    properly.<br>If it still doesn't work your VPS might be faulty.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    How do I put CloudFlare on my Domain?
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <b>First way</b> would be to host through the Tokyo Panel itself first withoutCloudflare.
Then after that is successfull you would change the name servers to
Cloudflare. (You'd have to make an account on Cloudflare)<br><br>
<b>Second way if you already put the domain on CloudFlare's
nameservers</b> you could will have to host by connecting to the IP directly of
your VPS with our wget command you can copy it in <b>My Panels</b>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    This is all still to complex I don't know how to host I just want to work!!
                </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    You could pay a moderator to host for you 60$ with domain included and
                    VPS you will have to give him your Tokyo Panel API to start.
                </div>
            </div>
        </div>

         <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    How do I make sure my website stays online long?
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    You can experiment and try different domain names or host multiple
                    domains or try subdomains.<br>
                    Also you can turn on Redirect all in "My Panels" when you're not using the
                    panel to preserve it
                </div>
            </div>
        </div>
    </div>
    </p>
</template>