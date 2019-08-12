<div class="tab-pane fade" id="product">
            	<form id="productForm">
            	<div class="row">
                	<div class="col-sm-3"><span class="form-control left-label">อายุของสินค้าใหม (วัน)</span></div>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control input-sm input-mini input-line" name="NEW_PRODUCT_DATE" id="newProductAge" value="<?php echo getConfig('NEW_PRODUCT_DATE'); ?>" />
                        <span class="help-block">กำหนดจำนวนวัน ที่จะแสดงไฮไลท์ว่าเป็นสินค้าใหม่</span>
                    </div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">สินค้าใหม่</span></div>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control input-sm input-mini input-line" name="NEW_PRODUCT_QTY" id="newProductQty" value="<?php echo getConfig('NEW_PRODUCT_QTY'); ?>" />
                    	<span class="help-block">กำหนดจำนวนสินค้าที่จะแสดงรายการสินค้าใหม่บนหน้าแรก (สำหรับลูกค้า และ พนักงานขาย) </span>
                    </div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">สินค้าหน้าแรก</span></div>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control input-sm input-mini input-line" name="FEATURES_PRODUCT" id="featuresProduct" value="<?php echo getConfig('FEATURES_PRODUCT'); ?>" />
                    	<span class="help-block">กำหนดจำนวนสินค้าที่จะแสดงเป็นรายการสินค้าแนะนำบนหน้าแรก (สำหรับลูกค้า และ พนักงานขาย) </span>
                    </div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">Stock Filter</span></div>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control input-sm input-mini input-line" name="MAX_SHOW_STOCK" id="stockFilter" value="<?php echo getConfig('MAX_SHOW_STOCK'); ?>" />
                    	<span class="help-block">กำหนดจำนวนสินค้าคงเหลือสูงสุดที่จะแสดงให้ลูกค้าเห็น (สำหรับลูกค้า) ถ้าไม่ต้องการใช้กำหนดเป็น 0 </span>
                    </div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">รูปแบบบาร์โค้ด</span></div>
                    <div class="col-sm-9">
                    	<?php $barcodeType = getConfig('BARCODE_TYPE'); ?>
                    	<select class="form-control input-sm input-mini input-line" name="BARCODE_TYPE" id="barcodeType">
                        	<option value="code39" <?php echo isSelected($barcodeType, 'code39'); ?>>CODE 39</option>
                            <option value="code93" <?php echo isSelected($barcodeType, 'code93'); ?>>CODE 93</option>
                            <option value="code128" <?php echo isSelected($barcodeType, 'code128'); ?>>CODE 128</option>
                            <option value="ean13" <?php echo isSelected($barcodeType, 'ean13'); ?>>EAN 13</option>
                        </select>
                        <span class="help-block">เลือก Format ของบาร์โค้ดที่ใช้กับเอกสารต่างๆ</span>
                    </div>
                    <div class="divider-hidden"></div>
                    
                    <div class="cols-sm-9 col-sm-offset-3">
                    	<button type="button" class="btn btn-sm btn-success input-mini" onClick="updateConfig('productForm')"><i class="fa fa-save"></i> บันทึก</button>
                    </div>
                    <div class="divider-hidden"></div>
                    
            	</div><!--/ row -->
                </form>
            </div>