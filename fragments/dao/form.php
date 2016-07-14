<div class="form-group">
    <?php if (isset($this->label) && $this->label != ''): ?>
        <label class="<?= (isset($this->labelClass) && $this->labelClass != '') ? trim($this->labelClass) . ' ' : '' ?>control-label"><?= $this->label ?></label>
    <?php endif; ?>
    <div<?= (isset($this->fieldClass) && $this->fieldClass != '' ? ' class="' . $this->fieldClass . '"' : '')  ?>>
        <?= $this->field ?>
    </div>
</div>
