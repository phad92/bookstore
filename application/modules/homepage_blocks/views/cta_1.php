<style>
.cta-background{
    height: 200px;
}
.cta-section {
    background: rgba(0, 0, 0, 0) url("https://images.pexels.com/photos/257740/pexels-photo-257740.jpeg?w=940&h=650&auto=compress&cs=tinysrgb") no-repeat scroll left top / cover;
    /* padding: 55px 0; */
}
.black-trans-bg {
    position: relative;
}
.black-trans-bg::before {
    background: #000 none repeat scroll 0 0;
    content: "";
    height: 100%;
    left: 0;
    opacity: 0.3;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 0;
}
.cta-title h2 {
    color: #fff;
    display: inline-block;
    font-size: 26px;
    line-height: 30px;
    padding: 16px 0;
    text-transform: uppercase;
}
.cta-title a {
    margin-top:26px;
    border: 1px solid #fff;
    color: #fff;
    display: inline-block;
    float: right;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 0;
    padding: 9px 18px;
    text-transform: uppercase;
}

</style>
<div class="cta-section black-trans-bg cta-background" style="margin: 15px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                <div class="cta-title">
                    <h2>New Book From Jonathan Kellerman (Night Moves)</h2>
                    <div>
                        <a href="#">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>