<div class="form-group">
    <?php if (isset($this->label) && $this->label != ''): ?>
        <label class="<?= $this->labelClass ?> control-label"><?= $this->label ?></label>
    <?php endif; ?>
    <div class="<?= $this->fieldClass ?>">
        <?= $this->field ?>
    </div>
</div>
