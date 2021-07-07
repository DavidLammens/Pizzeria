    <div id="melding" <?php echo $meldingService->hidden() ?>>
        <h3>Melding</h3>
        <div>
            <p><?php echo $meldingService->takeMelding() ?></p>
            <button id="meldingBtn" class="btn">Ok</button>
        </div>
    </div>