$(function(){var tmpl_user_details='<tmpl><h2>${username}</h2>\
                            <img src="${image}" width="67px" height="67px"/>\
                            <ul class="plain">\
                                <li>#${uid}</li>\
                                <li>${email}</li>\
                                <li><span>Score:</span> ${score}</li>\
                                <li data-uid="${uid}">\
                                    <a href="#" data-medal="contributor" class="medal {{if medal_contributor }}medal-green{{/if}}">Contributor</a>\
                                    <a href="#" data-medal="helper" class="medal {{if medal_helper }}medal-green{{/if}}">Helper</a>\
                                </li>\
                            </ul>\
                            <div class="privilages" data-uid="${uid}">\
                                <a href="#" data-priv="site" class="medal {{if site_priv > 1}}medal-gold{{else site_priv < 1}}medal-error{{/if}}">Site</a>\
                                <a href="#" data-priv="pm" class="medal {{if pm_priv > 1}}medal-gold{{else pm_priv < 1}}medal-error{{/if}}">PM</a>\
                                <a href="#" data-priv="forum" class="medal {{if forum_priv > 1}}medal-gold{{else forum_priv < 1}}medal-error{{/if}}">Forum</a>\
                                <a href="#" data-priv="pub" class="medal {{if pub_priv > 1}}medal-gold{{else pub_priv < 1}}medal-error{{/if}}">Publish</a>\
                            </div></tmpl>';$('.admin-module-user-manager input').on('keydown',function(e){if(e.which==13){e.preventDefault();return;}});$('.admin-module-user-manager input').on('keyup',function(e){var $this=$(this);delay(function(){$('.user-details').html("Loading...");var term=$this.val();$.getJSON('/api/?method=user.profile&user='+term,function(data){if(data.profile&&data.profile.username){$.each(data.profile.medals,function(){if(this.label=='Contributor'){data.profile.medal_contributor=true;}else if(this.label=='helper'){data.profile.medal_helper=true;}});$('.user-details').html($(tmpl_user_details).tmpl(data.profile));}else{$('.user-details').html("User not found");}});},500);});$('.admin-module-user-manager-editable, .admin-module-moderators-editable').on('click','a.medal',function(e){e.preventDefault();var $this=$(this),value;if($this.data('priv')){if($this.hasClass('medal-gold')){$this.removeClass('medal-gold');value=1;}else if($this.hasClass('medal-error')){$this.removeClass('medal-error');$this.addClass('medal-gold');value=2;}else{$this.addClass('medal-error');value=0;}
var data={};data.uid=$this.parent().data('uid');data.priv=$this.data('priv');data.priv_value=value;console.log(data);$.post('/api/?method=user.admin.priv',data);}else if($this.data('medal')){if($this.hasClass('medal-green')){$this.removeClass('medal-green');value=0;}else{$this.addClass('medal-green');value=1;}
var data={};data.uid=$this.parent().data('uid');data.medal=$this.data('medal');data.medal_value=value;console.log(data);$.post('/api/?method=user.admin.medal',data);}});var delay=(function(){var timer=0;return function(callback,ms){clearTimeout(timer);timer=setTimeout(callback,ms);};})();$('.flags a.remove').on('click',function(e){e.preventDefault();var $this=$(this),$row=$(this).closest('tr');$.getJSON('forum.php?action=flag.remove&id='+$row.attr('data-pid'),function(data){if(data.status===true){$row.slideUp();}});});$('.add-field').on('click',function(e){e.preventDefault();html='<div class="clr">\
                    <select style="width: auto" class="tiny" name="form_type[]">\
                        <option>Text</option>\
                        <option>Password</option>\
                    </select>\
                    <input type="text" placeholder="Label" class="span_6" name="form_label[]">\
                    <input type="text" placeholder="Name" class="span_6" name="form_name[]">\
                </div>';$(this).before(html);});$('.add-answer').on('click',function(e){e.preventDefault();html='<div class="clr">\
                    <select name="answer_method[]" style="width: auto" class="tiny">\
                        <option>GET</option>\
                        <option>POST</option>\
                    </select>\
                    <input name="answer_name[]" type="text" placeholder="Name" class="span_6">\
                    <input name="answer_value[]" type="text" placeholder="Value" class="span_6">\
                </div>';$(this).before(html);});});$(function(){$('.post-flags a.remove').on('click',function(e){e.preventDefault();var $this=$(this),$row=$this.closest('li');$.get($this.data('href'),function(data){$row.slideUp();});});var threadDeleteTmpl='<tmpl>\
                                <form>\
                                    <ul class="reasons plain">\
                                        <li>\
                                            <input type="radio" name="reason" id="reason1" value="1"/>\
                                            <h3><label for="reason1">It is off-topic</label></h3>\
                                            This thread is not relevant to any section of the site.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason2" value="2"/>\
                                            <h3><label for="reason2">It is a spoiler</label></h3>\
                                            This thread contains primarily answers or more detailed information than is necessary.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason3" value="3"/>\
                                            <h3><label for="reason3">It is spam</label></h3>\
                                            This thread is primarily an advertisement with no disclosure. It is not useful or relevant, but promotional.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason4" value="4"/>\
                                            <h3><label for="reason4">It is very low quality</label></h3>\
                                            This thread has severe formatting or content problems.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason5" value="5"/>\
                                            <h3><label for="reason5">It is not English</label></h3>\
                                            The HackThis!! community’s first language is English.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason6" value="6"/>\
                                            <h3><label for="reason6">No longer relevant</label></h3>\
                                            This thread is being removed just to tidy things up.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason7" value="7"/>\
                                            <h3><label for="reason7">Other</label></h3>\
                                            <div class="modal-reason-other hide">\
                                                <textarea name="other" placeholder="Add explanation"/>\
                                            </div>\
                                        </li>\
                                    </ul>\
                                    <input type="submit" class="button left" value="Delete thread"/>\
                                </form>\
                            </tmpl>';var postDeleteTmpl='<tmpl>\
                                <form>\
                                    <ul class="reasons plain">\
                                        <li>\
                                            <input type="radio" name="reason" id="reason1" value="1"/>\
                                            <h3><label for="reason1">It is off-topic</label></h3>\
                                            This post is not relevant to the thread.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason2" value="2"/>\
                                            <h3><label for="reason2">It is a spoiler</label></h3>\
                                            This post contains primarily answers or more detailed information than is necessary.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason3" value="3"/>\
                                            <h3><label for="reason3">It is spam</label></h3>\
                                            This post is primarily an advertisement with no disclosure. It is not useful or relevant, but promotional.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason4" value="4"/>\
                                            <h3><label for="reason4">It is very low quality</label></h3>\
                                            This post has severe formatting or content problems.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason5" value="5"/>\
                                            <h3><label for="reason5">It is not English</label></h3>\
                                            The HackThis!! community’s first language is English.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason6" value="6"/>\
                                            <h3><label for="reason6">No longer relevant</label></h3>\
                                            This post refers to a post that longer exists and is being removed just to tidy things up.\
                                        </li>\
                                        <li>\
                                            <input type="radio" name="reason" id="reason7" value="7"/>\
                                            <h3><label for="reason7">Other</label></h3>\
                                            <div class="modal-reason-other hide">\
                                                <textarea name="other" placeholder="Add explanation"/>\
                                            </div>\
                                        </li>\
                                    </ul>\
                                    <input type="submit" class="button left" value="Delete post"/>\
                                </form>\
                            </tmpl>';var postEditTmpl='<tmpl>\
                                <form>\
                                    <textarea></textarea>\
                                    <input type="submit" class="button left" value="Update post"/>\
                                </form>\
                            </tmpl>';var modal_thread_edit='hello',modal_thread_delete=$(threadDeleteTmpl).tmpl()[0].outerHTML,modal_post_edit=$(postEditTmpl).tmpl()[0].outerHTML,modal_post_delete=$(postDeleteTmpl).tmpl()[0].outerHTML;$('.thread-edit, .thread-delete, .post-edit, .post-delete').on('click',function(e){e.preventDefault();var title='',id=$(this).closest('li').data('id'),action;if($(this).hasClass('thread-delete')){action='admin.thread.remove';id=$('.forum-main').data('thread-id');}else if($(this).hasClass('post-delete')){action='admin.post.remove';}
if($(this).hasClass('thread-edit')){title='Edit thread';template=modal_thread_edit;}else if($(this).hasClass('thread-delete')){title='Delete thread';template=modal_thread_delete;}else if($(this).hasClass('post-edit')){title='Edit post';template=modal_post_edit;}else if($(this).hasClass('post-delete')){title='Delete post';template=modal_post_delete;}
$.createModal(title,template,function(){var $modal=this;$modal.find('.reasons input[type=radio]').on('change',function(){if($modal.find('#reason7:checked').length){$modal.find('.modal-reason-other').slideDown('fast');}else{$modal.find('.modal-reason-other').slideUp('fast');}});$modal.find('input[type=submit]').on('click',function(e){e.preventDefault();if(!$modal.find(':checked').length)
return false;var reason=$modal.find(':checked').attr('value'),other=$modal.find('textarea').val();data={reason:reason,extra:other,id:id}
$modal.height($modal.height());$modal.find('.modal-content').fadeOut('fast',function(){$.get('/files/ajax/forum.php?action='+action,data,function(){$modal.find('.modal-content').html($('<div>',{'html':"<i class='icon-good'></i>Thank you",'class':'thanks'})).fadeIn();});});});});});});$(function(){$('pre.bbcode_code_body').each(function(i,e){hljs.highlightBlock(e)});$('body').on('click','.bbcode_spoiler_head',function(e){e.preventDefault();$(this).toggleClass('active');});var $allVideos=$(".bbcode-youtube, .bbcode-vimeo");function resizeVideos(){$allVideos.each(function(){var $el=$(this);$el.removeAttr('height').height($el.width()*0.56);});}
$(window).resize(resizeVideos).resize();});(function(jQuery){var dataKey=jQuery('body').attr('data-key');if(typeof dataKey!=='string'||dataKey==='')return;var TOKEN_NAME='ajax_csrf_token';var TOKEN_VALUE=dataKey;var ALLOWED_HOSTNAMES=['www.crushit.fit','crushit.fit','localhost'];var TOKEN_STRING=TOKEN_NAME+'='+TOKEN_VALUE;jQuery.ajaxPrefilter(function(options){var tempLink=document.createElement("a");tempLink.href=options.url;var hostname=tempLink.hostname||window.location.hostname;if(ALLOWED_HOSTNAMES.indexOf(hostname)>-1){var urlParts=options.url.split('?');var queryString=urlParts[1];if(typeof queryString==='undefined'){queryString=TOKEN_STRING;}else if(queryString.indexOf(TOKEN_NAME)===-1){queryString=TOKEN_STRING+'&'+queryString;}
options.url=urlParts[0]+'?'+queryString;}});})(jQuery);var socket=null;if(typeof io!=='undefined'){socket=io.connect('https://www.hackthis.co.uk:8080/',{secure:true});}
var favcounter=new FavCounter();var counter_chat=0;var counter_notifications=0;$(function(){var username=$('body').attr('data-username');var key=$('body').attr('data-key');var feedTmpl='<tmpl>'+'    <li>'+'      <div class="col span_18">'+'        {{if type == "join"}}'+'            <i class="icon-user"></i><a href="/user/${username}">${username}</a>'+'        {{else type == "friend"}}'+'            <i class="icon-addfriend"></i><a href="/user/${username}">${username}</a> <span class="dark">and</span> <a href="/user/${username_2}">${username_2}</a>'+'        {{else type == "medal"}}'+'            <i class="icon-trophy colour-${uri}"></i><a href="/medals.php#${title.toLowerCase()}">${title}</a> <span class="dark">to</span> <a href="/user/${username}">${username}</a>'+'        {{else type == "comment"}}'+'            <i class="icon-comments"></i><a href="${uri}">${title}</a> <span class="dark">by</span> <a href="/user/${username}">${username}</a>'+'        {{else type == "forum_post"}}'+'            <i class="icon-chat"></i><a href="${uri}">${title}</a> <span class="dark">by</span> <a href="/user/${username}">${username}</a>'+'        {{else type == "favourite"}}'+'            <i class="icon-heart"></i><a href="${uri}">${title}</a> <span class="dark">by</span> <a href="/user/${username}">${username}</a>'+'        {{else type == "article"}}'+'            <i class="icon-books"></i><a href="${uri}">${title}</a> <span class="dark">by</span> <a href="/user/${username}">${username}</a>'+'        {{else type == "news"}}'+'            <i class="icon-article"></i><a href="${uri}">${title}</a> <span class="dark">by</span> <a href="/user/${username}">${username}</a>'+'        {{else type == "level"}}'+'            <i class="icon-good"></i><a href="${uri}">${title}</a> <span class="dark">by</span> <a href="/user/${username}">${username}</a>'+'        {{/if}}'+'        </div>'+'        <div class="col span_6 time right"><time class="short" datetime="${timestamp}"></time></div>'+'    </li>'+'</tmpl>';if(socket){socket.on('feed',function(data){var item=$(feedTmpl).tmpl(data);if($('.sidebar .feed ul').length){item.hide().prependTo($('.sidebar .feed ul')).slideDown();}else{var html=$('<ul>').append(item);$('.sidebar .feed .feed_loading').replaceWith(html);}});}else{$('.sidebar .feed .feed_loading').html('<strong>Feed offline</strong>');}
var lastUpdate=0;var UPDATE_INTERVAL_ACTIVE=10000;var UPDATE_INTERVAL_INACTIVE=30000;(function updateTimes(){uri='/files/ajax/notifications.php';$.post(uri,{last:lastUpdate},function(data){if(data.counts.events>0){$('.nav-extra-events').addClass('alert');$('#event-counter').fadeIn(500).text(data.counts.events);}else{$('.nav-extra-events').removeClass('alert');$('#event-counter').fadeOut(200);}
if(data.counts.pm>0){$('.nav-extra-pm').addClass('alert');$('#pm-counter').fadeIn(500).text(data.counts.pm);}else{$('.nav-extra-pm').removeClass('alert');$('#pm-counter').fadeOut(200);}
counter_notifications=data.counts.events+data.counts.pm;favcounter.set(counter_notifications+counter_chat);},'json');if($(window).data('isInactive')===true){setTimeout(updateTimes,UPDATE_INTERVAL_INACTIVE);}else{setTimeout(updateTimes,UPDATE_INTERVAL_ACTIVE);}})();var notificationsTmpl='<tmpl>'+'{{if seen == 0}}'+'    <li class="new">'+'{{else}}'+'    <li>'+'{{/if}}'+'        <time class="short" datetime="${timestamp}"></time>'+'{{if username}}'+'        <a href="/user/${username}">'+'            <img class="left" width="28" height="28" src="${img}"/>'+'        </a>'+'{{/if}}'+'    {{if type == "friend"}}'+'            {{if status == 1}}'+'                You accepted a friend request from <a href="/user/${username}">${username}<a/>'+'            {{else}}'+'                <a href="/user/${username}">${username}<a/> sent you a friend request'+'                <a href="#" class="addfriend" data-uid="${uid}">Accept</a> | <a href="#" class="removefriend" data-uid="${uid}">Decline</a>'+'            {{/if}}'+'    {{else type == "friend_accepted"}}'+'            <a href="/user/${username}">${username}<a/> accepted your friend request'+'    {{else type == "medal"}}'+'            You have been awarded <a href="/medals.php#${label.toLowerCase()}"><div class="medal medal-${colour}">${label}</div></a><br/>'+'    {{else type == "comment_reply"}}'+'            <a href="/user/${username}">${username}<a/> replied to your comment on <a href="${slug}">${title}</a><br/>'+'    {{else type == "comment_mention"}}'+'            <a href="/user/${username}">${username}<a/> mentioned you in a comment on <a href="${slug}">${title}</a><br/>'+'    {{else type == "forum_post"}}'+'            <a href="/user/${username}">${username}<a/> posted in <a href="${slug}">${title}</a><br/>'+'    {{else type == "forum_mention"}}'+'            <a href="/user/${username}">${username}<a/> mentioned you in <a href="${slug}">${title}</a><br/>'+'    {{else type == "article"}}'+'            Your article has been published <a href="${slug}">${title}</a><br/>'+'    {{else type == "mod_contact"}}'+'            <a href="/user/${username}">${username}</a> replied to your <a href="/contact?view=${ticket}">ticket</a><br/>'+'    {{else type == "mod_report"}}'+'            <a href="/user/${username}">${username}</a> created a new report, <a href="/contact?report=${report}">view report</a><br/>'+'    {{/if}}'+'    </li>'+'</tmpl>';var inboxTmpl='<tmpl>'+'{{if seen == 0}}'+'    <li class="new">'+'{{else}}'+'    <li>'+'{{/if}}'+'        <a class="show-conversation" data-conversation="${pm_id}" href="/inbox/${pm_id}">'+'            <time class="short" datetime="${timestamp}"></time>'+'            {{each(i,user) users}}'+'                ${user.username}{{if users.length-1 != i}},{{/if}}'+'            {{/each}}'+'            <br/>'+'            <span class="dark">{{html message}}</span>'+'        </a>'+'    </li>'+'</tmpl>';var composeForm='<form class="send"><label for="to">To:</label>'+'<input name="to" class="suggest hide-shadow" data-suggest-at="false" data-suggest-max="2" id="to" autocomplete="off"/><br/>'+'<label for="message">Message:</label><br/>'+'<textarea class="hide-shadow"></textarea>'+'<input type="submit" class="button" value="Send"/>'+'<span class="error"></span>';var replyForm='<form class="send">'+'<label for="message">Reply:</label><br/>'+'<textarea class="hide-shadow"></textarea>'+'<input type="submit" class="button" value="Send"/>'+'<span class="error"></span>';var messagesTmpl='<tmpl>'+'{{if seen == 0}}'+'    <li class="new">'+'{{else}}'+'    <li>'+'{{/if}}'+'        <time class="short" datetime="${timestamp}"></time>'+'{{if username}}'+'        <a href="/user/${username}">'+'            <img class="left" width="28" height="28" src="${img}"/>'+'            ${username}'+'        </a><br/>'+'{{else}}'+'        <img class="left" width="28" height="28"/>'+'        <span class="white">You</span><br/>'+'{{/if}}'+'        {{html message}}'+'    </li>'+'</tmpl>';var dropdown=$('#nav-extra-dropdown');var icons=$('.nav-extra').parent();$('.nav-extra').on('click',function(e){e.preventDefault();e.stopPropagation();var parent=$(this).parent();if(dropdown.is(":visible")&&(($(this).hasClass('nav-extra-pm')&&parent.hasClass('active-pm'))||($(this).hasClass('nav-extra-events')&&parent.hasClass('active-events')))){dropdown.slideUp(200);icons.removeClass('active');return false;}
icons.removeClass('active');if($(this).hasClass('nav-extra-pm')){var uri='/files/ajax/inbox.php?list';icons.removeClass('active-events');parent.addClass('active active-pm');}else if($(this).hasClass('nav-extra-events')){var uri='/files/ajax/notifications.php?events';icons.removeClass('active-pm');$(this).removeClass('alert');$('#event-counter').fadeOut(200);parent.addClass('active active-events');}else{return false;}
$.getJSON(uri,function(data){var items=data.items;if(items.length||(items.items&&items.items.length)){if(parent.hasClass('active-events')){var html=$('<ul>');var count=data.friends.length-1;$.each(data.friends,function(index){tmpli=$(notificationsTmpl).tmpl(this);if(count==index){tmpli.addClass('last-request');}
html.append(tmpli);});var items=$(notificationsTmpl).tmpl(items.items);var more=$("<li>",{class:"more"});$('<a>',{text:"View More",href:"/alerts.php"}).appendTo(more);html.append(items).append(more);}else{var items=$(inboxTmpl).tmpl(items);var messagesHTML=$('<div>',{class:"messages"});$('<a>',{text:"New Message",class:"toggle-compose more",href:"/inbox/compose"}).appendTo(messagesHTML);var list=$('<ul>').append(items);list.appendTo(messagesHTML);$('<a>',{text:"Full View",class:"more",href:"/inbox/"}).appendTo(messagesHTML);var extraHTML=$('<div>',{class:"extra"});html=$('<div>',{class:"message-container"}).append(messagesHTML);html.append(extraHTML)}}else{if(parent.hasClass('active-events'))
var html='<div class="center empty"><i class="icon-globe icon-4x"></i>No notifications available</div>';else{var messagesHTML=$('<div>',{class:"messages"});$('<a>',{text:"New Message",class:"toggle-compose more",href:"/inbox/compose"}).appendTo(messagesHTML);var list=$('<div class="center empty"><i class="icon-envelope-alt icon-4x"></i>No messages available</div>');list.appendTo(messagesHTML);$('<a>',{text:"Full View",class:"more",href:"/inbox/"}).appendTo(messagesHTML);var extraHTML=$('<div>',{class:"extra"});html=$('<div>',{class:"message-container"}).append(messagesHTML);html.append(extraHTML)}}
dropdown.html(html).slideDown(200);});bindCloseNotifications();});$('#global-nav').on('click','.addfriend, .removefriend',function(e){e.preventDefault();var $this=$(this);if($this.hasClass('addfriend'))
var uri='/files/ajax/user.php?action=friend.add&uid=';else
var uri='/files/ajax/user.php?action=friend.remove&uid=';uri+=$this.attr('data-uid');uri+="&token="+$('body').attr('data-key');$.getJSON(uri,function(data){if(data.status)
$this.closest('li').slideUp();});}).on('click','.toggle-compose',function(e){e.preventDefault();e.stopPropagation();var container=$('#global-nav .message-container');if(container.hasClass('show-extra')){container.removeClass('show-extra');}else{var composeHTML=container.children('.extra');composeHTML.html('');$('<a>',{text:"Back to Inbox",class:"toggle-compose more",href:"/inbox"}).appendTo(composeHTML);composeHTML.append(composeForm);$('<a>',{text:"Full View",class:"more full-view-via-storage",href:"/inbox/compose"}).appendTo(composeHTML);container.addClass('show-extra');$('#nav-extra-dropdown .suggest').autosuggest();}}).on('click','.show-conversation',function(e){e.preventDefault();var $this=$(this);var id=$this.attr('data-conversation');var uri='/files/ajax/inbox.php?view&id='+id;$.getJSON(uri,function(data){data=data.items;if(data.length){var container=$('#global-nav .message-container');items=$('<ul>',{class:'scroll'}).append($(messagesTmpl).tmpl(data));items.append($('<li>').append(replyForm));items.find('form').attr('data-conversation',id);var messagesHTML=container.children('.extra');messagesHTML.html('');$('<a>',{text:"Back to Inbox",class:"toggle-compose more",href:"/inbox"}).appendTo(messagesHTML);messagesHTML.append(items);$('<a>',{text:"Full View",class:"more full-view-via-storage",href:"/inbox/"+id}).appendTo(messagesHTML);container.addClass('show-extra');$('#global-nav .scroll').mCustomScrollbar();if(container.find('.new').length){$('#global-nav .scroll').mCustomScrollbar("scrollTo","li.new:first");}else{$('#global-nav .scroll').mCustomScrollbar("scrollTo","li:nth-last-child(2)");}
$this.parent().removeClass('new');}});}).on('click','form.send input.button',function(e){e.preventDefault();e.stopPropagation();var data={};$form=$(this).closest('form');$error=$form.find('span.error');data.body=$form.find('textarea').val();$error.text('');if($form.find('#to').length){data.to=$form.find('#to').val();if(!data.to){$error.text("Missing recipient");return;}}else if($form.attr('data-conversation')){data.pm_id=$form.attr('data-conversation');}else{return;}
if(!data.body){$error.text("Missing body");return;}
var uri='/files/ajax/inbox.php?send';$.post(uri,data,function(data){if(data.status){if(data.message){data.seen=true;data.img="";var $msg=$(messagesTmpl).tmpl(data);$msg.hide();$form.closest('li').before($msg);$msg.slideDown(function(){$form.closest('.scroll').mCustomScrollbar("scrollTo","bottom");});$form.find('textarea').val('');var pm_id=$form.attr('data-conversation');$item=$('#nav-extra-dropdown .messages ul li > a[data-conversation="'+pm_id+'"]').parent();$item.detach();$item.find('span.dark').html('<i class="icon-reply"></i> '+data.message);var date=new Date();$item.find('time').attr('datetime',date.toISOString()).text('secs');$item.prependTo($('#nav-extra-dropdown .messages ul'));}else{$sent=$('<div class="center empty fill"><i class="icon-ok-sign icon-4x"></i>Message Sent</div>').hide();$form.replaceWith($sent);$sent.fadeIn();}}else
$error.text("Error sending message");},'json');}).on('click','.full-view-via-storage',function(e){e.stopPropagation();if(window.localStorage){if($('#to').length)
window.localStorage.recipients=$('#to')[0].value;window.localStorage.content=$('form.send textarea')[0].value;}});$('body').on('click','.messages-new',function(e){e.preventDefault();e.stopPropagation();var to=$(this).attr('data-to');var composeHTML=$('<div>',{class:"compose"});composeHTML.append(composeForm);$('<a>',{text:"Full View",class:"more",href:"/inbox/compose?to="+to}).appendTo(composeHTML);dropdown.html(composeHTML).slideDown(200);$('#nav-extra-dropdown .suggest').val(to).autosuggest();$('#nav-extra-dropdown textarea').focus();icons.removeClass('active active-events active-pm');bindCloseNotifications();});function bindCloseNotifications(){$(document).bind('click.extra-hide',function(e){if($(e.target).closest('#nav-extra-dropdown').length!=0&&$(e.target).not('.nav-extra'))return true;hideNotifications();});}
function hideNotifications(){dropdown.slideUp(200);icons.removeClass('active active-events active-pm');$(document).unbind('click.extra-hide');}});$.fn.autosuggest=function(){this.each(function(){$self=$(this);$self.keyup(function(event){var $this=$(this);var auto=$this.attr('data-suggest-at')==='false'?false:true;var caret=$this.getCursorPosition().end;var val=this.value+' ';var word=/\S+$/.exec(val.slice(0,val.indexOf(' ',caret)));if(!word){$this.siblings('.autosuggest').remove();return;}
word=word[0];if(auto){if(word.substr(0,1)!=='@'){$this.siblings('.autosuggest').remove();return;}
word=word.substr(1);}
var max=$this.attr('data-suggest-max')?$this.attr('data-suggest-max'):5;if(word.length<3)
return false;$.get('/files/ajax/autosuggest.php',{user:word,max:max},function(data){$this.siblings('.autosuggest').remove();var list=$('<ul>',{class:'autosuggest'});if(!auto){list.addClass('autosuggest-alt');}
if(data.status==false)
return;for(var i=0;i<data.users.length;++i){user=data.users[i];var icon=$('<i>',{class:'icon-addfriend'});var link=$('<a>',{text:user.username,href:'#'+user.username});if(user.friends==1)
link.append(icon);$('<li>').append(link).appendTo(list);}
$this.after(list);},'json');$(document).bind('click.suggest-hide',function(e){if($(e.target).closest('.autosuggest').length!=0||$(e.target).hasClass('suggest'))return true;$('.autosuggest').remove();$(document).unbind('click.suggest-hide');});});$self.parent().on('click','.autosuggest a',function(e){var $this=$(this);e.preventDefault();e.stopPropagation();var $self=$this.closest('.autosuggest').prev();var auto=$self.attr('data-suggest-at')==='false'?false:true;$this.closest('.autosuggest').remove();var insert=this.hash.slice(1);if(!auto)insert+=",";tmp=$self.val()+' ';var caret=$self.getCursorPosition().end;var wordEnd=tmp.indexOf(' ',caret);var word=/\S+$/.exec(tmp.slice(0,wordEnd));if(auto)
var start=tmp.substr(0,wordEnd-word[0].length+1);else
var start=tmp.substr(0,wordEnd-word[0].length);var end=tmp.substr(wordEnd);var tmp=start+insert+end;$self.val(tmp).focus().setCursorPosition(start.length+insert.length+1);});});};function searchsuggest(){var $this=$('.nav-search input'),value=$this[0].value;if(value.length<3){$this.parent().siblings('.searchsuggest').remove();return false;}
$.get('/files/ajax/autosuggest.php',{search:value},function(data){if(data.status){var suggest=$('<div>',{class:'searchsuggest'});if(data.data.users){var users=data.data.users;title=$('<h3>',{text:'Users'});suggest.append(title);len=users.length<5?users.length:5;for(var i=0;i<len;++i){link=$('<a>',{text:users[i].username,href:'/user/'+users[i].username});suggest.append(link);}}
if(data.data.articles){var articles=data.data.articles;title=$('<h3>',{text:'Articles'});suggest.append(title);len=articles.length<5?articles.length:5;for(var i=0;i<len;++i){link=$('<a>',{html:articles[i].title,href:'/articles/'+articles[i].slug});suggest.append(link);}}
if(data.data.forum){var forum=data.data.forum;title=$('<h3>',{text:'Forum'});suggest.append(title);len=forum.length<5?forum.length:5;for(var i=0;i<len;++i){link=$('<a>',{html:forum[i].title,href:'/forum/'+forum[i].slug});suggest.append(link);}}
$this.parent().siblings('.searchsuggest').remove();$this.parent().after(suggest);}else{$this.parent().siblings('.searchsuggest').remove();}},'json');$(document).bind('click.search-hide',function(e){if($(e.target).closest('.searchsuggest').length!=0||$(e.target).hasClass('suggest'))return true;$('.searchsuggest').remove();$(document).unbind('click.search-hide');});}
$(function(){var timer=null;$('.suggest').autosuggest();$('.nav-search input').keyup(function(event){if(timer){clearTimeout(timer);}
timer=setTimeout(searchsuggest,200);});});