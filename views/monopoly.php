<?php
$pageTitle = "Monopoly";

ob_start();
?>

<div class="auth-page-content overflow-hidden p-0" id="monopoly">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="text-center py-5">
                    <h1 class="mb-4">Cờ Tỷ Phú</h1>

                    <div class="d-flex justify-content-center mt-4">
                        <div id="board">
                            <div class="top">
                                <div class="tile corner active" data-name="Bắt đầu"><div class="player">🚶</div>Bắt đầu</div>
                                <div class="tile property" data-name="Phố A" data-price="60" data-rent="10">Phố A ($60)
                                </div>
                                <div class="tile chance" data-name="Cơ hội">Cơ hội</div>
                                <div class="tile property" data-name="Phố B" data-price="60" data-rent="10">Phố B ($60)
                                </div>
                                <div class="tile tax" data-name="Thuế Thu Nhập" data-amount="200">Thuế (-$200)</div>
                                <div class="tile property" data-name="Nhà Ga 1" data-price="200" data-rent="50">Nhà Ga 1
                                    ($200)
                                </div>
                                <div class="tile property" data-name="Phố C" data-price="100" data-rent="20">Phố C
                                    ($100)
                                </div>
                                <div class="tile special jail" data-name="Nhà Tù">Nhà Tù</div>
                            </div>

                            <div class="right">
                                <div class="tile property" data-name="Phố D" data-price="120" data-rent="25">Phố D
                                    ($120)
                                </div>
                                <div class="tile chance" data-name="Cơ hội">Cơ hội</div>
                                <div class="tile property" data-name="Nhà Máy Điện" data-price="150" data-rent="30">Nhà
                                    Máy
                                    Điện ($150)
                                </div>
                                <div class="tile property" data-name="Phố E" data-price="140" data-rent="30">Phố E
                                    ($140)
                                </div>
                            </div>

                            <!-- Cột bên phải -->

                            <!-- Ô trung tâm dành cho #info -->
                            <div id="info">
                                <p id="diceResult" class="mb-4"></p>
                                <button class="btn btn-outline-primary spin-btn btn-load mb-3" id="rollDice">
                                    <span class="d-flex align-items-center">
                                        <span class="flex-grow-1">
                                            Lắc Xí Ngầu
                                        </span>
                                        <span class="spinner-border flex-shrink-0 d-none ms-2" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </span>
                                    </span>
                                </button>
                                <p>Tiền: <b><span id="playerMoney">1500</span> $</b></p>
                            </div>

                            <div class="bottom">
                                <div class="tile corner" data-name="Miễn Phí Đậu Xe">Miễn Phí Đậu Xe</div>
                                <div class="tile tax" data-name="Thuế Sang Trọng" data-amount="100">Thuế (-$100)</div>
                                <div class="tile property" data-name="Phố K" data-price="260" data-rent="70">Phố K
                                    ($260)
                                </div>
                                <div class="tile property" data-name="Nhà Ga 3" data-price="200" data-rent="50">Nhà Ga 3
                                    ($200)
                                </div>
                                <div class="tile property" data-name="Phố H" data-price="200" data-rent="50">Phố H
                                    ($200)
                                </div>
                                <div class="tile chance" data-name="Cơ hội">Cơ hội</div>
                                <div class="tile property" data-name="Phố I" data-price="220" data-rent="55">Phố I
                                    ($220)
                                </div>
                                <div class="tile corner" data-name="Vào Tù">Vào Tù</div>
                            </div>

                            <div class="left">
                                <div class="tile property" data-name="Phố G" data-price="180" data-rent="40">Phố G
                                    ($180)
                                </div>
                                <div class="tile tax" data-name="Thuế Sang Trọng" data-amount="100">Thuế (-$100)</div>
                                <div class="tile property" data-name="Phố F" data-price="160" data-rent="35">Phố F
                                    ($160)
                                </div>
                                <div class="tile property" data-name="Nhà Ga 2" data-price="200" data-rent="50">Nhà Ga 2
                                    ($200)
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>

<?php
$pageContent = ob_get_clean();

ob_start(); ?>
<script src="<?= home_url("assets/js/pages/monopoly.js") ?>"></script>
<?php
$additionJs = ob_get_clean();
