<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php');

$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
$ms  = $c->home_timeline(); // done
$uid_get = $c->get_uid();
$uid = $uid_get['uid'];
$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
$user_friends = $c->friends_by_id($uid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>法国房源发布</title>
	
	<!-- script resources -->
	
	<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=488430092" type="text/javascript" charset="utf-8"></script>
	<script src="http://tjs.sjs.sinajs.cn/t35/apps/opent/js/frames/client.js" type="text/javascript" charset="utf-8"></script>
	<script src="jquery-1.7.1.js" type="text/javascript" charset="utf-8"></script>
	<script src="bootstrap/js/bootstrap.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">	
	 
		var token = '<?php echo $_SESSION['token']['access_token'] ?>';

		$(document).ready(function() {
		
			//reconstruct the data from the server to be appropriate for the data source for typeahead functions
			constructTypeaheadSrc = function(value){
			
				var arrayData = value.data;
				var searchHintSrc = new Array();
				
				for(i in arrayData){
				
					searchHintSrc.push(arrayData[i].nickname);
				}
				
				return searchHintSrc;
			
			};
		
			var autocomplete = $('#searchHint').typeahead().on('keyup', function(ev){
	
	            ev.stopPropagation();
	            ev.preventDefault();
	
	            //filter out up/down, tab, enter, and escape keys
	            if( $.inArray(ev.keyCode,[37,38,39,40,9,13,27]) === -1 ){
	
	                var self = $(this);
	                var arr = new Array();
	                var queryKeyword = $(this).val();
	                
	                //set typeahead source to empty
	                self.data('typeahead').source = [];
	
	                //active used so we aren't triggering duplicate keyup events
	                if( !self.data('active') && self.val().length > 0){
	
	                    self.data('active', true);
	                    
	                    var request = $.ajax({
					
						  url: "https://api.weibo.com/2/search/suggestions/at_users.json",
						  type: "GET",
						  data: {
						  	access_token : token,
						  	q:encodeURI(queryKeyword),
						  	type:0
						  },
						  dataType: "jsonp"
						}).done(function(result){
							
							arr = constructTypeaheadSrc(result);
							console.log("arr",arr);
							//set your results into the typehead's source 
		                    self.data('typeahead').source = arr;
		
		                    //trigger keyup on the typeahead to make it search
		                    self.trigger('keyup');
		
		                    //All done, set to false to prepare for the next remote query.
		                    self.data('active', false);
						});
	
	                }
	            }
	        });
        
        });
	
	</script>

	<!-- css resources -->
	<link rel="stylesheet" href="style/app.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<meta name="author" content="Yunpeng Pan">
	<!-- Date: 2012-03-18 -->

</head>

<body>
	<!-- A modal window that I designed at the beginning to indicate the user to log in before they use the app, to discuss whether it's still neccessay -->
	<div id="myModal" class="modal hide fade" style="display: none; ">
            <div class="modal-header">
              <a class="close" data-dismiss="modal">×</a>
              <center><h3>欢迎</h3></center>
            </div>
            <div class="modal-body">
              <center><h4>使用前请先登陆</h4></center>
              <br />
              <br />
           </div>   
            <div class="modal-footer">
            <a href="#" class="btn btn-success" data-dismiss="modal">知道啦</a>
            </div>
    </div>

	<div id="main-content">
		<div class="hero-unit">
		  <h1>微博快速发布房源信息</h1>
		  <p>法国地区最快速高效的发布方式</p>
		  <?=$user_message['screen_name']?>,您好！	  		
		</div>
		<div id="form-immo-info">
			<form class="form-horizontal" _lpchecked="1">
			        <fieldset>
			          <!--  <center><legend>填写房屋信息</legend></center>-->
			          
			          <div class="control-group">
			            <label class="control-label" for="input01">城市</label>
			            <div class="controls">
			              <input type="text" class="input-xlarge" id="input01" placeholder="小巴黎，大巴黎，马赛 等等">			             
			            </div>
			          </div>
		          
			          <div class="control-group">
			            <label class="control-label" for="select01">房型</label>
			            <div class="controls">
			              <select id="select01">
			                
			                <option>公寓</option>
			                <option>写字楼</option>
			                <option>Studio</option>
			                <option>别墅</option>
			                
			              </select>
			            </div>
			          </div>
			          
			          <div class="control-group">
			            <label class="control-label" for="appendedInput">面积</label>
			            <div class="controls">
			              <div class="input-append">
			                <input class="span2" id="appendedInput" size="16" type="text"><span class="add-on">m<sup>2</sup></span>
			              </div>			              
			            </div>
			          </div>
			          
			          <div class="control-group">
			            <label class="control-label" for="inlineCheckboxes">租住周期</label>
			            <div class="controls">
			            
			            <label class="checkbox inline">
			                <input type="checkbox" id="inlineCheckbox2" value="option2">长租
			              </label>
			              <label class="checkbox inline">
			                <input type="checkbox" id="inlineCheckbox1" value="option1">短租
			              </label>
			              
			            </div>
			          </div>
			          
			          <div class="control-group">
			            <label class="control-label" for="inlineCheckboxes">材料</label>
			            <div class="controls">
			            
			            <label class="checkbox inline">
			                <input type="checkbox" id="inlineCheckbox2" value="option2">家具
			              </label>
			              <label class="checkbox inline">
			                <input type="checkbox" id="inlineCheckbox1" value="option1">房补
			              </label>
			              <label class="checkbox inline">
			                <input type="checkbox" id="inlineCheckbox2" value="option2">住房合同
			              </label>
			              <label class="checkbox inline">
			                <input type="checkbox" id="inlineCheckbox2" value="option2">住房证明
			              </label>
			              <label class="checkbox inline">
			                <input type="checkbox" id="inlineCheckbox2" value="option2">EDF
			              </label>
			            </div>
			          </div>
			          
			          <div class="control-group">
			            <label class="control-label" for="appendedInput">房租</label>
			            <div class="controls">
			              <div class="input-append">
			                <input class="span2" id="appendedInput" size="16" type="text"><span class="add-on">€</span>
			              </div>			              
			            </div>
			          </div>
			          
			          <div class="control-group">
			            <label class="control-label" for="appendedInput">中介费</label>
			            <div class="controls">
			              <div class="input-append">
			                <input class="span2" id="appendedInput" size="16" type="text"><span class="add-on">€</span>
			              </div>			              
			            </div>
			          </div>
			          
			          <div class="control-group">
			            <label class="control-label" for="appendedInput">押金</label>
			            <div class="controls">
			              <div class="input-append">
			                <input class="span2" id="appendedInput" size="16" type="text"><span class="add-on">€</span>
			              </div>			              
			            </div>
			          </div>
			          
			          
					<div class="control-group">
			            <label class="control-label" for="input01">交通</label>
			            <div class="controls">
			              <input type="text" class="input-xlarge" id="input01" placeholder="地铁，公交，RER">			             
			            </div>
			          </div>
			          
			          <div class="control-group">
			            <label class="control-label" for="input01">联系人</label>
			            <div class="controls">
			              <input type="text" class="input-xlarge" id="input01" placeholder="电话，QQ，Email, MSN">			             
			            </div>
			          </div>
			          
			          
			          <div class="control-group">
			            <label class="control-label" for="prependedInput">站外链接</label>
			            <div class="controls">
			              <div class="input-prepend">
			                <span class="add-on">http://</span><input class="span2" id="prependedInput" size="16" type="text">
			              </div>
			              <p class="help-block">照片外部链接，战法链接,华人街链接</p>
			            </div>
			          </div>
			          			   			          
        
			          <div class="control-group">
			            <label class="control-label" for="fileInput">照片</label>
			            <div class="controls">
			              <input class="input-file" id="fileInput" type="file">
			            </div>
			          </div>
			          
			          <div class="control-group">
			            <label class="control-label" for="searchHint">求扩散</label>
			            <div class="controls">
			              <input type="text" id="searchHint" class="span3" style="margin: 0 auto;" data-provide="typeahead" placeholder="@">			
			            </div>
			          </div>  
			          
			         
			          <div class="form-actions">
			            <button type="submit" class="btn btn-primary">发布微博</button>
			            <button class="btn">取消</button>
			            
			          </div>
			        </fieldset>
			      </form>
		</div>
	</div>
</body>

</html>