<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/WebPage" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
    <title><?= $pagetitle ?> - <?= SITE_NAME ?></title>
    <meta name="description" content="<?= (empty($metadesc) ? SITE_METADESC : $metadesc) ?>" />
    <meta itemprop="name" content="<?= SITE_NAME ?> - <?= SITE_STRAPLINE ?>">
    <meta name="description" content="<?= (empty($metadesc) ? SITE_METADESC : $metadesc) ?>" />
    <link rel="canonical" href="<?= current_url() ?>" />

    <meta property="og:title" content="<?= SITE_NAME ?> - <?= SITE_STRAPLINE ?>" />
    <meta property="og:site_name" content="<?= SITE_NAME ?>" />  
    <meta property="og:image" content="<?= site_url('assets/img/layout/logo.png') ?>" />

    <meta name="viewport" content="initial-scale=1.0">
    <meta name="viewport" content="maximum-scale=1.0">
    <meta name="viewport" content="user-scalable=no">
    <meta name="viewport" content="width=device-width">
    <meta name="apple-mobile-web-app-capable" content="yes" />

    <link rel="shortcut icon" href="<?= site_url('assets/img/favicon.ico') ?>" type="image/x-icon" /> 

    <link href='http://fonts.googleapis.com/css?family=Roboto+Slab' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?= site_url('assets/css/bootstrap.css') ?>" type="text/css" charset="utf-8">
    <link rel="stylesheet" href="<?= site_url('assets/css/bootstrap-responsive.min.css') ?>" type="text/css" charset="utf-8">
    <link rel="stylesheet" href="<?= site_url('assets/css/chosen.css') ?>" type="text/css" charset="utf-8">    
    <link rel="stylesheet" href="<?= site_url('assets/css/styles.css') ?>" type="text/css" charset="utf-8">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="<?= site_url('assets/js/bootstrap.min.js') ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?= site_url('assets/js/chosen.jquery.min.js') ?>" type="text/javascript" charset="utf-8"></script>    

    <script type="text/javascript">
        $(document).ready(function(){
            // Tooltip for top right menu
            $('#top-menu .btn-group [title]').tooltip({
              container: 'body'
            });

            // Mobile menu slider
            $('.menuopen').on('click', function(){
                $('#fixed-menu-mobile').toggle("slide");
            });

            // Advert affix
            height = $('.sidebar').height() - $('.sidebar [data-spy=affix]').height() - 30; // 30 = height of btn-group
            $('.sidebar [data-spy=affix]').attr('data-offset-top', height);
			
			$('#add-post').on('click',	function(e){
				$('#addModal').modal();
				e.preventDefault();
			});
			
			$(".chosen-add").chosen({
				width:'220px',
				"disable_search": true
			}).change( function(){
				changeAddFieldGroup();
			});
			
			$('#addModal #link').on('blur', function(e){				
				$('#addModal #err').hide();
				$('#addModal #desc').hide();
				
				if($(this).val() != ''){
					getLinkMeta($(this).val(), '#addModal');
				}
			});
			
			$('#formAdd #link').on('blur', function(e){				
				$('#formAdd #err').hide();
				$('#formAdd #desc').hide();
				
				if($(this).val() != ''){
					getLinkMeta($(this).val(), '#formAdd');
				}
			});
			
			$('#addModal form').on('submit', function(e){				
				if($('.chosen-add').val() == 'link'){
					if($('#addModalLink #link').val() == '' || $('#addModalLink #title').val() == ''){
						alert('Sorry, all fields are required.');	
						e.preventDefault();
					}
				} else {
					if($('#addModalDiscussion #comment').val() == '' || $('#addModalDiscussion #title').val() == ''){
						alert('Sorry, all fields are required.');	
						e.preventDefault();
					}
				}
			});
			
            // Side category links
            $(document).on('click', '.nav-link', function(e){
                if ($('.form-search input[name=type]').length > 0) {
                    $('.form-search input[name=type]').val($(this).attr('rel'));
                } else {
                    $('.form-search').append('<input type="hidden" name="type" value="'+ $(this).attr('rel') +'">');
                }
                $('.form-search input[type=text]').val($('.form-search input[type=text]').val()); // Hack to get the form text submitting
                $('.form-search').submit();
                e.preventDefault();
            });
			
			changeAddFieldGroup();
        });
		
		/**
		 * Change the groups of field when adding a post
		 */
		function changeAddFieldGroup()
		{
			if($(".chosen-add").val() == 'link'){
				$('#addModalLink').show();
				$('#addModalDiscussion').hide();
				$('#addModalBtn').html('Add Link');
				
				$('#addModalLink input').attr('required', 'required');
				$('#addModalDiscussion input').attr('required', false);
			} else {
				$('#addModalLink').hide();
				$('#addModalDiscussion').show();
				$('#addModalBtn').html('Start Discussion');
				
				$('#addModalLink input').attr('required', false);
				$('#addModalDiscussion input').attr('required', 'description');
			}
		}
		
		/**
		  * Using the meta tags of the supplied url,
		  * fetch the title, description and image to display as a preview
		  */
		function getLinkMeta(url, id) {
		  // create the yql query (encoded!) with the url provided
		  var q = 'http://query.yahooapis.com/v1/public/yql?q=' + encodeURIComponent('select * from html where url="' + url + '" and xpath="//title|//head/meta"') + '&format=json&callback=?';
		  
		  $(id +' .progress').show();
		  
		  // submit the query to the YQL web service
		  $.ajax({
			type: 'GET',
			url: q, 
			dataType: 'jsonp',
			  success: function(data, textStatus) {
			  results = data.query.results;
		 	  
			  $(id +' .progress').hide();
		 
			  // make sure we have some data to work with
			  if (results) {
				// will contain only the data we want to display
				var result = {};
		 
				// use the html title as the title
				result.title = results.title;
		 
				// loop through the meta tags to grab the image & description
				$.each(data.query.results.meta, function(key, val){
				  if (val.content) {	    
					// description
					if (val.name && val.name == 'description') {
					  result.description = val.content;
					}
		 
					// og:image (Facebook image)
					// starts with http:// and has a . 4 characters from the end
					if (val.content.substring(0, 7) == 'http://' && val.content.charAt(val.content.length - 4) == '.') {
					  if (val.content != 'undefined') {
						result.img = val.content;
					  }
					}
				  }
				});
		 
                console.log(result)
                if(Object.prototype.toString.call( result.title ) === '[object Array]'){
                    title = result.title[1]
                } else {
                    title = result.title
                }
                title = title.replace(/^,|,$/g,'');
		 		$(id +' #link_title').val(title);
				
				if(typeof result.description != 'undefined') {
					$(id +' #desc').html(result.description);
					$(id +' #desc').show();
				}	
			  } else {
				$(id +' #err').html('That URL cannot be scraped, please check it before posting.').show();
			  }
			}
		  });
		}
    </script>

    <?= $_scripts ?>
    <?= $_styles ?>
</head>
<body class="body-articles">   

    <div id="top-stripe"></div>
    
    <div id="top-menu">
        <div class="btn-group">
        	<?php if(empty($this->user)): ?>
	            <a href="<?= site_url('login') ?>" class="btn" data-toggle="tooltip" title="Login or Register" data-placement="left"><i class="icon-user"></i></a>
            <?php else: ?>
            	<a href="<?= site_url('add') ?>" id="add-post" class="btn" data-toggle="tooltip" title="Add Post" data-placement="left"><i class="icon-pencil"></i></a>
	            <a href="#" class="btn dropdown-toggle" data-toggle="dropdown" title="My account"><i class="icon-user"></i> <?= $this->user['username'] ?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                	<li><?= anchor('search/posts/1?u='.$this->user['username'], 'View your posts') ?></li>
                    <li><?= anchor('user/details', 'Edit your account') ?></li>
                    <li><?= anchor('u/'.$this->user['username'], 'View your profile') ?></li>
                    <li class="divider"></li>
                    <li><?= anchor('login/logout', 'Log out') ?></li>                    
                </ul>
            <?php endif; ?>			
        </div> <!-- .btn-group -->
    </div>
    
    <div id="top-menu-mobile">
	    <?php if(!empty($this->user)): ?>	 
    		<a href="<?= site_url('add') ?>" id="add-post" class="btn"><i class="icon-pencil"></i></a>
        <?php endif; ?>
        
        <div class="btn-group">
        	<?php if(empty($this->user)): ?>	            
    	        <a href="<?= site_url('login') ?>" class="btn"><i class="icon-user"></i></a>
	            <a href="<?= site_url('register') ?>" class="btn"><i class="icon-book"></i></a>
            <?php else: ?>
	            <a href="#" class="btn dropdown-toggle" data-toggle="dropdown" title="My account"><i class="icon-user"></i> <?= $this->user['username'] ?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                	<li><?= anchor('search/posts/1?u='.$this->user['username'], 'View your posts') ?></li>
                    <li><?= anchor('user/details', 'Edit your account') ?></li>
                    <li><?= anchor('u/'.$this->user['username'], 'View your profile') ?></li>
                    <li class="divider"></li>
                    <li><?= anchor('login/logout', 'Log out') ?></li>                  
                </ul>
            <?php endif; ?>	
        </div> <!-- .btn-group -->

        <button type="button" class="btn btn-navbar menuopen pull-right">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="clr"></div>
    </div>



    <div id="fixed-menu">
        <div class="fixed-content">
            <div class="fixed-content-2">
                <div class="logo">
                    <a href="<?= site_url(''); ?>"><?= SITE_NAME ?></a>
                </div>
    
                <div class="search-container">
                    <form class="form-search" action="<?= site_url('search/posts/1') ?>" method="get">
                    	
                        <div class="search-icon">
                            <button type="submit" class="btn btn-link"><i class="icon-search"></i></button>
                        </div>
                    
                     	<?php
                        	$frmQ = $this->input->get('q');
                        	$frmType = $this->input->get('type');
						?>
                        <input type="text" name="q" id="query" class="search-input" placeholder="enter any keyword to search..." value="<?=(!empty($frmQ) ? $frmQ : '') ?>" >
                        <?php if(!empty($frmType)): ?>
                        <input type="hidden" name="type" value="<?= $frmType ?>">
                        <?php endif; ?>
                    </form>
                    <div class="clr"></div>
                </div>
                <div class="nav-link-container">
                    <a class="nav-link <?= ((!empty($is_search) && empty($frmType)) ? 'active' : '') ?> " rel="" href="<?= site_url('') ?>"><div class="nav-stripe"></div>Everything</a>
                    <a class="nav-link <?= ($frmType == 'links' ? 'active' : '') ?>" rel="links" href="<?= site_url('search/posts/1?type=links') ?>"><div class="nav-stripe"></div>Links</a>
                    <a class="nav-link <?= ($frmType == 'discussions' ? 'active' : '') ?>" rel="discussions" href="<?= site_url('search/posts/1?type=discussions') ?>"><div class="nav-stripe"></div>Discussions</a>
                </div>
    
                <div class="footer-spacer"></div>
                <div class="footer-container">
                    <!--<a href="about">About</a>&nbsp;&nbsp;-->
                    <a href="guidelines">Guidelines</a>
                </div>
            </div>
        </div>
    </div>
    
    <div id="fixed-menu-mobile">
        <div class="fixed-content">
            <div class="fixed-content-2">
                <div class="logo">
                    <a href="<?= site_url(''); ?>"><?= SITE_NAME ?></a>
                </div>
    
                <div class="search-container">
                    <form class="form-search" action="<?= site_url('search/posts/1') ?>" method="get">
                        
                        <div class="search-icon">
                            <button type="submit" class="btn btn-link"><i class="icon-search"></i></button>
                        </div>
                    
                        <?php
                            $frmQ = $this->input->get('q');
                            $frmType = $this->input->get('type');
                        ?>
                        <input type="text" name="q" id="query" class="search-input" placeholder="enter any keyword to search..." value="<?=(!empty($frmQ) ? $frmQ : '') ?>" >
                        <?php if(!empty($frmType)): ?>
                        <input type="hidden" name="type" value="<?= $frmType ?>">
                        <?php endif; ?>
                    </form>
                    <div class="clr"></div>
                </div>
                <div class="nav-link-container">
                    <a class="nav-link <?= ((!empty($is_search) && empty($frmType)) ? 'active' : '') ?> " rel="" href="<?= site_url('') ?>"><div class="nav-stripe"></div>Everything</a>
                    <a class="nav-link <?= ($frmType == 'links' ? 'active' : '') ?>" rel="links" href="<?= site_url('search/posts/1?type=links') ?>"><div class="nav-stripe"></div>Links</a>
                    <a class="nav-link <?= ($frmType == 'discussions' ? 'active' : '') ?>" rel="discussions" href="<?= site_url('search/posts/1?type=discussions') ?>"><div class="nav-stripe"></div>Discussions</a>
                </div>
    
    
                <div class="footer-spacer"></div>
                <div class="footer-container">
                    <a href="about">About</a>&nbsp;&nbsp;
                    <a href="guidelines">Guidelines</a>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="outer-wrap">
        <div id="main-listing-wrapper">
            <section class="static">            
                <div class="row-fluid">
                    <div class="<?= (empty($sidebar) ? 'span12' : 'span10') ?>">
                        <?= $message ?>
                        <?= $content ?>
                    </div>
                    <?php if(!empty($sidebar)): ?>
                    <div class="span2 sidebar <?= (isset($show_sidebar_on_mobile) ? '' : 'visible-desktop') ?>">
                        <?= $sidebar ?>
                    </div>  
                    <?php endif; ?>
                </div>
            </section>
        </div> 
    </div>
    
    <div class="modal hide fade" id="addModal">
        <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        	<h3>Add a link or start a discussion</h3>
        </div>
        <form class="form-horizontal" action="<?= site_url('add') ?>" method="post">
        <div class="modal-body">
            <div class="control-group">
                <label class="control-label" for="type">What are you posting?</label>
                <div class="controls">        	    	    
                    <select name="type" class="chosen-add">
                        <option value="link" selected="selected">Link</option>
                        <option value="discussion">Discussion</option>
                    </select>
                </div>
            </div>
            
            <div id="addModalLink">    
                <div class="control-group">            	
                    <label class="control-label" for="link">Link</label>
                    <div class="controls">        	    	    
                        <input type="text" name="link" id="link" required>
                        <div class="progress progress-striped active hide help-block">
                            <div class="bar" style="width: 100%;"></div>
                        </div>
                        <div class="help-block text-error" id="err"></div>
                        <div class="help-block" id="desc"></div>
                    </div>
                </div>
                    
                <div class="control-group">
                    <label class="control-label" for="link_title">Title</label>
                    <div class="controls">        	    	    
                        <input type="text" name="link_title" id="link_title" required>
                    </div>
                </div>
            </div>
            
            <div id="addModalDiscussion" class="hide">                          
                <div class="control-group">
                    <label class="control-label" for="disc_title">Title</label>
                    <div class="controls">        	    	    
                        <input type="text" name="disc_title" id="disc_title">
                        <p class="help-block">Keep it descriptive and engaging</p>
                    </div>
                </div>
                                
                <div class="control-group">            	
                    <label class="control-label" for="comment">First comment</label>
                    <div class="controls">        	    	    
                        <textarea name="comment" id="comment" style="height: 130px; width: 320px;"></textarea>
                        <p class="help-block">Get the ball rolling</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	<p class="pull-left muted">Please stick to our <?=anchor('guidelines', 'guidelines', 'target="_blank"')?>.</p>
       		<a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Cancel</a>
        	<button type="submit" class="btn btn-primary" id="addModalBtn">Add Link</button>
        </div>
        </form>
    </div>

</body>
</html>