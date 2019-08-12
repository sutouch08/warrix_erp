<div class="tab-pane fade" id="document">
	<form id="documentForm">
    <div class="row">
      <div class="col-sm-12">
        <h4 class="title">กำหนดเลขที่เอกสารและจำนวนหลักในการรันเลขที่เอกสาร</h4>
      </div>
    </div>
    <hr class="margin-bottom-15" />
    <div class="row">
    	<div class="col-sm-4"><span class="form-control left-label">ขายสินค้า</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_ORDER" value="<?php echo getConfig('PREFIX_ORDER'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_ORDER" value="<?php echo getConfig('RUN_DIGIT_ORDER'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ฝากขาย[โอนคลัง]</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_CONSIGNMENT" value="<?php echo getConfig('PREFIX_CONSIGNMENT'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_CONSIGNMENT" value="<?php echo getConfig('RUN_DIGIT_CONSIGNMENT'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ฝากขาย[ใบกำกับ]</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_CONSIGN" value="<?php echo getConfig('PREFIX_CONSIGN'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_CONSIGN" value="<?php echo getConfig('RUN_DIGIT_CONSIGN'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ตัดยอดฝากขาย</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_CONSIGN_SOLD" value="<?php echo getConfig('PREFIX_CONSIGN_SOLD'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_CONSIGN_SOLD" value="<?php echo getConfig('RUN_DIGIT_CONSIGN_SOLD'); ?>" /></div>
      <div class="divider-hidden"></div>

      <!--
      <div class="col-sm-4"><span class="form-control left-label">ใบสั่งซื้อ</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_PO" value="<?php echo getConfig('PREFIX_PO'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_PO" value="<?php echo getConfig('RUN_DIGIT_PO'); ?>" /></div>
      <div class="divider-hidden"></div>
      -->

      <div class="col-sm-4"><span class="form-control left-label">รับสินคาเข้าจากการซื้อ</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RECEIVE" value="<?php echo getConfig('PREFIX_RECEIVE'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_RECEIVE" value="<?php echo getConfig('RUN_DIGIT_RECEIVE'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">รับสินค้าเข้าจากการแปรสภาพ</span></div>
      <div class="col-sm-2">
      	<input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RECEIVE_TRANSFORM" value="<?php echo getConfig('PREFIX_RECEIVE_TRANSFORM'); ?>" />
      </div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2">
      	<input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_RECEIVE_TRANSFORM" value="<?php echo getConfig('RUN_DIGIT_RECEIVE_TRANSFORM'); ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">เบิกแปรสภาพ</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line" name="PREFIX_TRANSFORM" value="<?php echo getConfig('PREFIX_TRANSFORM'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line" name="RUN_DIGIT_TRANSFORM" value="<?php echo getConfig('RUN_DIGIT_TRANSFORM'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ยืมสินค้า</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_LEND" value="<?php echo getConfig('PREFIX_LEND'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_LEND" value="<?php echo getConfig('RUN_DIGIT_LEND'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">เบิกสปอนเซอร์</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_SPONSOR" value="<?php echo getConfig('PREFIX_SPONSOR'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_SPONSOR" value="<?php echo getConfig('RUN_DIGIT_SPONSOR'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">เบิกอภินันท์</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_SUPPORT" value="<?php echo getConfig('PREFIX_SUPPORT'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_SUPPORT" value="<?php echo getConfig('RUN_DIGIT_SUPPORT'); ?>" /></div>
      <div class="divider-hidden"></div>

      <!--
      <div class="col-sm-4"><span class="form-control left-label">คืนสินค้าจากการขาย</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RETURN_ORDER" value="<?php echo getConfig('PREFIX_RETURN_ORDER'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_RETURN_ORDER" value="<?php echo getConfig('RUN_DIGIT_RETURN_ORDER'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">คืนสินค้าจากการสปอนเซอร์</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RETURN_SPONSOR" value="<?php echo getConfig('PREFIX_RETURN_SPONSOR'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_RETURN_SPONSOR" value="<?php echo getConfig('RUN_DIGIT_RETURN_SPONSOR'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">คืนสินค้าจากอภินันท์</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RETURN_SUPPORT" value="<?php echo getConfig('PREFIX_RETURN_SUPPORT'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_RETURN_SUPPORT" value="<?php echo getConfig('RUN_DIGIT_RETURN_SUPPORT'); ?>" /></div>
      <div class="divider-hidden"></div>
      -->

      <div class="col-sm-4"><span class="form-control left-label">คืนสินค้าจากการยืม</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RETURN_LEND" value="<?php echo getConfig('PREFIX_RETURN_LEND'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_RETURN_LEND" value="<?php echo getConfig('RUN_DIGIT_RETURN_LEND'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">กระทบยอด</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_CONSIGN_CHECK" value="<?php echo getConfig('PREFIX_CONSIGN_CHECK'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_CONSIGN_CHECK" value="<?php echo getConfig('RUN_DIGIT_CONSIGN_CHECK'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">โอนสินค้าระหว่างคลัง</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_TRANSFER" value="<?php echo getConfig('PREFIX_TRANSFER'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_TRANSFER" value="<?php echo getConfig('RUN_DIGIT_TRANSFER'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ย้ายพื้นที่จัดเก็บ</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_MOVE" value="<?php echo getConfig('PREFIX_MOVE'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_MOVE" value="<?php echo getConfig('RUN_DIGIT_MOVE'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ปรับยอดสต็อก</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_ADJUST" value="<?php echo getConfig('PREFIX_ADJUST'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_ADJUST" value="<?php echo getConfig('RUN_DIGIT_ADJUST'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">นโยบายส่วนลด</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_POLICY" value="<?php echo getConfig('PREFIX_POLICY'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_POLICY" value="<?php echo getConfig('RUN_DIGIT_POLICY'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">เงื่อนไขส่วนลด</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RULE" value="<?php echo getConfig('PREFIX_RULE'); ?>" /></div>
      <div class="col-sm-2"><span class="form-control left-label">Run digit</span></div>
      <div class="col-sm-2"><input type="text" class="form-control input-sm input-mini input-line digit" name="RUN_DIGIT_RULE" value="<?php echo getConfig('RUN_DIGIT_RULE'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4 col-sm-offset-3">
      	<button type="button" class="btn btn-sm btn-success input-mini" onClick="checkDocumentSetting()"><i class="fa fa-save"></i> บันทึก</button>
      </div>
      <div class="divider-hidden"></div>

    </div><!--/ row -->
  </form>
</div>
