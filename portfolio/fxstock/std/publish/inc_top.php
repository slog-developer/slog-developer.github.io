
	<div class="row_wrap">
		<div class="thumb_img">
			<div id="image-preview" class="pb_thumb_img" style='background-image: url("<?=$funding_view['funding_img_name']?>");background-size: contain; background-repeat: no-repeat;background-size: 360px 200px;'>
				<label for="img_upload" id="image-label">파일선택</label>
				<input type="file" name="img_upload" id="img_upload" />
			</div>
		</div><!-- thumb_img end -->
		<div class="slide_content_text_v2">
			<h1><?=$mem_info['mem_corporation_name']?></h1>
			<div class="pb_content_input">
				<textarea  id="txt_top_memo" name="txt_top_memo" placeholder="펀딩을 표현할 수 있는 문장을 입력해주세요. 최대 3줄까지 가능" rows="4" maxlength="105" style="overflow:hidden"><?=$funding_view['funding_top_memo']?></textarea>
			</div>
		</div>                            
	</div>