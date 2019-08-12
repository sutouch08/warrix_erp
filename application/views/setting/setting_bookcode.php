<div class="tab-pane fade" id="bookcode">
	<form id="bookcodeForm">
    <div class="row">
      <div class="col-sm-12">
        <h4 class="title">กำหนดค่าเล่มเอกสารให้ตรงกับ FORMULA</h4>
      </div>
    </div>
    <hr class="margin-bottom-15" />
    <div class="row">
    	<div class="col-sm-4"><span class="form-control left-label">ขายสินค้า (SO)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line boocode" name="BOOKCODE_ORDER" value="<?php echo getConfig('BOOKCODE_ORDER'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบสั่งขาย" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">เบิกอภินันท์ (SO)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_SUPPORT" value="<?php echo getConfig('BOOKCODE_SUPPORT'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบสั่งขาย" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">เบิกสปอนเซอร์ (SO)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_SPONSOR" value="<?php echo getConfig('BOOKCODE_SPONSOR'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบสั่งขาย" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">สั่งซื้อ (PO)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_PO" value="<?php echo getConfig('BOOKCODE_PO'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบสั่งซ์้อ" ให้ตรงกับ FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">รับสินค้าจากการซื้อ (BI)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_BI" value="<?php echo getConfig('BOOKCODE_BI'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบรับสินค้า" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">โอนสินค้าระหว่างคลัง (TR)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_TRANSFER" value="<?php echo getConfig('BOOKCODE_TRANSFER'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบโอนสินค้าคลัง" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ยืมสินค้า (TR)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_LEND" value="<?php echo getConfig('BOOKCODE_LEND'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบโอนสินค้า" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ฝากขาย[โอนคลัง] (TR)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_CONSIGNMENT" value="<?php echo getConfig('BOOKCODE_CONSIGNMENT'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบโอนสินค้า" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ตัดยอดฝากขาย[เปิดใบกำกับ] (SO)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_CONSIGN_SOLD" value="<?php echo getConfig('BOOKCODE_CONSIGN_SOLD'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบสั่งขาย" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">เบิกแปรสภาพ (WR)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_TRANSFORM" value="<?php echo getConfig('BOOKCODE_TRANSFORM'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบเบิกแปรสภาพ" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ใบรับสินค้าจากการแปรสภาพ (FR)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_RECIEVE_TRANSFORM" value="<?php echo getConfig('BOOKCODE_RECEIVE_TRANSFORM'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบรับสินค้าจากการแปรสภาพ" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ลดหนี้ขาย (SM)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line" name="BOOKCODE_RETURN_ORDER" value="<?php echo getConfig('BOOKCODE_RETURN_ORDER'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบลดหนี้" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ลดหนี้ฝากขาย (SM)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_RETURN_CONSIGN" value="<?php echo getConfig('BOOKCODE_RETURN_CONSIGN'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบลดหนี้" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-4"><span class="form-control left-label">ปรับปรุงสต็อก (AJ)</span></div>
      <div class="col-sm-8">
        <input type="text" class="form-control input-sm input-mini input-line bookcode" name="BOOKCODE_ADJUST" value="<?php echo getConfig('BOOKCODE_ADJUST'); ?>" />
        <span class="help-block">กำหนดรหัสเล่มเอกสาร "ใบปรับปรุงสต็อก" ให้ตรงกับ FORMULA เพื่อส่งออกไป FORMULA</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-9 col-sm-offset-3">
      	<button type="button" class="btn btn-sm btn-success input-mini" onClick="updateConfig('bookcodeForm')"><i class="fa fa-save"></i> บันทึก</button>
      </div>
      <div class="divider-hidden"></div>

    </div><!--/ row -->
  </form>
</div>
