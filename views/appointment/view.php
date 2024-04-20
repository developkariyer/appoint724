<div >
    <div class="row">
        <div class="col-md-12">

                <div class="time-grid">
                    <?php for ($t=0; $t<24; $t++): ?>
                        <div class="time-slot"><?= str_pad($t, 2, '0', STR_PAD_LEFT) ?>:00 - <?= str_pad($t+1, 2, '0', STR_PAD_LEFT) ?>:00</div>
                    <?php endfor; ?>
                </div>

        </div>
    </div>
</div>

<?php

$this->registerCss(<<<CSS
.time-grid {
    display: grid;
    grid-template-columns: 1fr;
    grid-template-rows: repeat(24, 1fr);
    grid-gap: 0px;
}

.time-slot {
    padding: 10px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}


.business-hours {
    background-color: #d1e7dd;
}
CSS);














