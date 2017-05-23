<?= $this->getContent() ?>
<div class="page-header">
    <h2>Connexion</h2>
</div>


<?= $this->tag->form(['index/start', 'id' => 'registerForm', 'onbeforesubmit' => 'return false']) ?>
<fieldset>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="control-group">
                <?= $form->label('login', ['class' => 'control-label']) ?>
                <div class="controls">
                    <?= $form->render('login', ['class' => 'form-control']) ?>
                    <div class="alert alert-warning" id="login_alert">
                        <strong>Warning!</strong> Please enter your login
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="control-group">
                <?= $form->label('password', ['class' => 'control-label']) ?>
                <div class="controls">
                    <?= $form->render('password', ['class' => 'form-control']) ?>
                    <div class="alert alert-warning" id="password_alert">
                        <strong>Warning!</strong> Please provide a valid password
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="form-actions">
                <?= $this->tag->submitButton(['Connexion', 'class' => 'btn btn-primary', 'onclick' => 'return SignUp.validate();']) ?>
                <?= $this->tag->endform() ?>
            </div>
        </div>
    </div>
</fieldset>
