<!-- THÔNG TIN KHÁCH HÀNG -->
<div class="client_details_tab">
    <div class="text_header">
        <span>
            Thông Tin Khách Hàng
        </span>
    </div>

    <div>
        <div class="form-group colum-row row">
            <div class="col-sm-12">
                <input type="text" name="client_full_name" id="client_full_name" value="<?php echo isset($_SESSION['client_full_name']) ? $_SESSION['client_full_name'] : ''; ?>" class="form-control" placeholder="Họ và Tên">
                <?php if(isset($errors['client_full_name'])): ?>
                <div class="invalid-feedback" id="required_fname" style="display: block;">
                    <?php echo $errors['client_full_name']; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <input type="email" name="client_email" id="client_email" value="<?php echo isset($_SESSION['client_email']) ? $_SESSION['client_email'] : ''; ?>" class="form-control" placeholder="Email">
                <?php if(isset($errors['client_email'])): ?>
                <div class="invalid-feedback" id="required_email" style="display: block;">
                    <?php echo $errors['client_email']; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-sm-6">
                <input type="text" name="client_phone_number" id="client_phone_number" value="<?php echo isset($_SESSION['client_phone_number']) ? $_SESSION['client_phone_number'] : ''; ?>" class="form-control" placeholder="Số điện thoại">
                <?php if(isset($errors['client_phone_number'])): ?>
                <div class="invalid-feedback" id="required_phone" style="display: block;">
                    <?php echo $errors['client_phone_number']; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group colum-row row">
            <div class="col-sm-12">
                <input type="text" name="client_delivery_address" id="client_delivery_address" value="<?php echo isset($_SESSION['client_delivery_address']) ? $_SESSION['client_delivery_address'] : ''; ?>" class="form-control" placeholder="Địa chỉ giao hàng">
                <?php if(isset($errors['client_delivery_address'])): ?>
                <div class="invalid-feedback" id="required_delivery_address" style="display: block;">
                    <?php echo $errors['client_delivery_address']; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Thông báo kết quả AJAX -->
    <div id="ajax-message-customer" class="mt-3" style="display: none;"></div>
</div> 