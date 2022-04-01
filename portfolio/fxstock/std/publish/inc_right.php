<div class="recruit_content">
	<ul class="chat_list mgt15">
		<li>
			<p>증권선택</p>
			<div class="chat_list_input">
				<select class="investor_box" id="ddl_funding_type" name="ddl_funding_type" style="width:110px;height:20px;">
					<option value="1">주식형</option>
					<option value="2">채권형</option>
				</select>
			</div>
		</li>
		<li>
			<p>상세선택</p>
			<div class="chat_list_input">
				<select class="investor_box" id="ddl_funding_type_detail_stock" name="ddl_funding_type_detail_stock" style="width:110px;height:20px;">
					<option value="" selected>선택</option>
					<option value="1">보통주</option>
					<option value="2">우선주</option>
					<option value="3">상환우선주</option>
					<option value="4">전환우선주</option>
					<option value="5">상환전환우선주</option>
				</select>

				<select class="investor_box" id="ddl_funding_type_detail_bond" name="ddl_funding_type_detail_bond" style="display:none;" style="width:110px;height:20px;">
					<option value="" selected>선택</option>
					<option value="1">일반회사채</option>
					<option value="2">이익참가부사채</option>
					<option value="3">전환사채</option>
					<option value="4">신주인수권부사채</option>
				</select>
			</div>
		</li>
		<li>
			<p>투자 단계</p>
			<div class="chat_list_input">
				<select class="investor_box" id="ddl_funding_invest_type" name="ddl_funding_invest_type" style="width:110px;">
					<option value="">선택</option>
					<option value="0">Seed</option>
					<option value="1">Sereis A</option>
					<option value="2">Sereis B</option>
					<option value="3">Sereis C</option>
				</select>
			</div>
		</li>							
	</ul>
</div><!-- recruit_content end -->			
