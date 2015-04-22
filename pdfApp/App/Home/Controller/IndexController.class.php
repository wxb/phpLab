<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
		$this->display();
	}
	public function score() {
		$name = I('post.name');
		$question_1 = I('post.q1');
		$question_2 = I('post.q2');
		$question_3 = I('post.q3');
		
		if ( ('' == $question_1) || ('' == $question_2) || ('' == $question_3) || ('' == $name)) {
			# code...
			$err_url = U('Index/index');
			$this->assign('url',$err_url);
			$this->display('nullError');
		}else {
			$score = 0;
			1 == $question_1 && $score++;
			1 == $question_2 && $score++;
			1 == $question_3 && $score++;

			//convert score to a percentage
    		$score = $score / 3 * 100;

			if($score < 50) {
		      // this person failed
				$this->display('notPass');
		      
		    }  else {
		      // create a string containing the score to one decimal place
		      $score = number_format($score, 1);
			  $this->assign('name',$name);
			  $this->assign('score',$score);
		      $this->display('isPass');
		    }
		}
	}
	public function pdflib() {
		$name = I('post.name');
		$score = I('post.score');
		if(!$name || !$score) {
			echo 'name and score is null';
		}else {
			// 开始制作pdf文档
			// 注意：PDFlib的使用也是有两种：面向对象和面向过程
			// 区别是：对于手册中的所有方法都是面向过程的方法，在使用面向对象时，只需要把前面的前缀'PDF_'用对象调用替换即可，
			// 同时忽略方法中的'resource $pdfdoc' 参数
			// 例如我们可以创建一个pdf对象  $pdf = new \PDFlib();
			// 也可以使用 $pdf = pdf_new();创建一个pdf资源句柄
			try {
				$date = date('F d, Y');  // 获取系统当前时间
				//  实例化一个PDFlib对象，注意我们的PDFlib由于是在php的扩展库中，所以这里记得前面加上'\'，thinkPHP此时就回去全区命名空间下
				//  查找这个类问价
				$pdf = new \PDFlib();
				// PDF_begin_document() 这个方法在内存中创建一个pdf文档
				if(false == $pdf->begin_document('','')) {
					die('Error: '.$pdf->get_errmsg());
				}

				$width = 792;
				$height = 612;
				// 向文档中添加新的页面
				$pdf->begin_page_ext($width,$height,'');
			
				// 开始定义并画出边框（pdf中的长度和位置的单位均是：像素）
				$inset = 20; // 边框和页面边缘的距离
				$border = 10; // 边框宽度
				$inner = 2; // 边框和两条边线的距离
				// PDF_rect 方法是画出一个矩形，注意坐标原点是 左下角
				$pdf->rect($inset-$inner,$inset-$inner,$width-2*($inset-$inner),$height-2*($inset-$inner));
				$pdf->stroke(); // PDF_stroke 描绘用当前颜色和路径宽度描绘出路径
				
				$pdf->setlinewidth($border);   // PDF_setlinewidth 设置线条宽度
				$pdf->rect($inset+$border/2,
                   $inset+$border/2,
                   $width-2*($inset+$border/2),
                   $height-2*($inset+$border/2));
    			$pdf->stroke();

				$pdf->setlinewidth(1.0);
				$pdf->rect($inset+$border+$inner,
						$inset+$border+$inner,
						$width-2*($inset+$border+$inner),
						$height-2*($inset+$border+$inner)
						);
				$pdf->stroke();
				// 添加标题
				$fontname = 'Times-Roman';
				// PDF_load_font 方法是：搜索和准备字体
				$font = $pdf->load_font($fontname,'winansi','');
				$pdf->setcolor('fill', 'rgb', 1, 0, 0, 0); // PDF_setcolor  设置 填充颜色和描绘路径颜色
				$pdf->setfont($font,20);
				$pdf->show_xy($date,50,490);  // PDF_show_xy 在给的的位置输出文本内容
				$pdf->setcolor('fill', 'rgb', 0, 0, 0, 0); // red
				$pdf->setfont($font,48);
				$start_x = ($width-$pdf->stringwidth('PHP Certification',$font,'12'))/2;
				$pdf->show_xy('PHP Certification',$start_x,490);
				
				// 添加内容
				$font = $pdf->load_font($fontname,'iso8859-1','');
				$pdf->setfont($font,26);
				$start_x = 70;
				$pdf->show_xy('This is to certify that:',$start_x,430);
				$pdf->show_xy(strtoupper($name),$start_x+90,391);

				$font = $pdf->load_font($fontname,'iso8859-1','');
				$pdf->setfont($font,20);
				$pdf->show_xy('has demonstrated that they are certifiable by passing a rigorous exam', $start_x, 340);
    			$pdf->show_xy('consisting of three multiple choice questions.',$start_x, 310);
                    
    			$pdf->show_xy("$name obtained a score of $score".'%.', $start_x, 260);
                    
    			$pdf->show_xy('The test was set and overseen by the ', $start_x, 210);
    			$pdf->show_xy('Fictional Institute of PHP Certification', $start_x, 180);
    			$pdf->show_xy("on $date.", $start_x, 150);
    			$pdf->show_xy('Authorised by:', $start_x, 100);

				// 添加签名
				// PDF_load_image  打开图像文件
				$signature = $pdf->load_image('png',WEB_ROOT."/Public/images/signature.png",'');
				$pdf->fit_image($signature,200, 75, '');  // PDF_fit_image 将图片放到指定位置
				$pdf->close_image($signature);  // 关闭文件
				
				// 开始画出星型图章 
				$pdf->setcolor('fill', 'rgb', 0, 0, .4, 0);  // 设置PDF_fill方法用的颜色
				$pdf->setcolor('stroke', 'rgb', 0, 0, 0, 0); // 设置PDF_stroke方法用的颜色
				// 画出左侧飘带
				$pdf->moveto(630, 150);  // PDF_moveto 将画图点移动到指定位置
				$pdf->lineto(610, 55);   // PDF_lineto 从当前点画出一条线到指定位置
				$pdf->lineto(632, 69);
				$pdf->lineto(646, 49);
				$pdf->lineto(666, 150);
				$pdf->closepath();   // PDF_closepath 关闭当前路径
				$pdf->fill();  // PDF_fill 用指定颜色填充到路径内 

				// outline ribbon 1
				$pdf->moveto(630, 150);
				$pdf->lineto(610, 55);
				$pdf->lineto(632, 69);
				$pdf->lineto(646, 49);
				$pdf->lineto(666, 150);
				$pdf->closepath();
				$pdf->stroke();

				// draw ribbon 2
				$pdf->moveto(660, 150);
				$pdf->lineto(680, 49);
				$pdf->lineto(695, 69);
				$pdf->lineto(716, 55);
				$pdf->lineto(696, 150);
				$pdf->closepath();
				$pdf->fill();

				// -> outline ribbon 2
				$pdf->moveto(660, 150);
				$pdf->lineto(680, 49);
				$pdf->lineto(695, 69);
				$pdf->lineto(716, 55);
				$pdf->lineto(696, 150);
				$pdf->closepath();
				$pdf->stroke();


				$pdf->setcolor('fill', 'rgb', 1, 0, 0, 0); // red

				//调用自定义方法，画出图章礼花
				$this->draw_star(665, 175, 32, 57, 10, $pdf, true);

				//outline rosette
				$this->draw_star(665, 175, 32, 57, 10, $pdf, false);

				// PDF_end_page 结束当前页
				
				// 这里如果需要继续制作第二页，第三页。。。。
				$pdf->end_page_ext("");

					$pdf->set_info("Creator", "hello.php");
				$pdf->set_info("Author", "Rainer Schaaf");
				$pdf->set_info("Title", "Hello world (PHP)!");

				$pdf->begin_page_ext(595, 842, "");

				$font = $pdf->load_font("Helvetica-Bold", "winansi", "");

				$pdf->setfont($font, 24.0);
				$pdf->set_text_pos(50, 700);
				$pdf->show("Hello world!");
				$pdf->continue_text("(says PHP)");
				$pdf->end_page_ext("");
				// 第二页结束，看明白了吗？

				// PDF_end_document 结束文档 
				$pdf->end_document("");
				// PDF_get_buffer 得到PDF输出缓存
				$data = $pdf->get_buffer();

				// 设置浏览器头信息
				header('Content-type: application/pdf');
				header('Content-disposition: inline; filename=test.pdf');
				header('Content-length: ' . strlen($data));

				// 输出PDF
				echo $data;
			}
			catch (PDFlibException $e) {
				die("PDFlib exception occurred in hello sample:\n" .
						"[" . $e->get_errnum() . "] " . $e->get_apiname() . ": " .
						$e->get_errmsg() . "\n");
			}
			catch (Exception $e) {
					die($e);
			}

		}
	}
	// 画礼花图章方法
	// 具体算法自行理解，所用方法上面都有所说明，
	function draw_star($centerx, $centery, $points, $radius, $point_size, $pdf, $filled)  {
		$inner_radius = $radius-$point_size;

		for ($i = 0; $i<=$points*2; $i++)  {
				$angle= ($i*2*pi())/($points*2);

				if($i%2) {
						$x = $radius*cos($angle) + $centerx;
						$y = $radius*sin($angle) + $centery;
				} else {
						$x = $inner_radius*cos($angle) + $centerx;
						$y = $inner_radius*sin($angle) + $centery;
				}

				if($i==0) {
						$pdf->moveto($x, $y);
				} else if ($i==$points*2) {
						$pdf->closepath();
				} else {
						$pdf->lineto($x, $y);
				}
		}
		if($filled) {
				$pdf->fill_stroke();
		} else {
				$pdf->stroke();
		}
	}

	public function pdf() {
		try {
				$p = new \PDFlib();

				/*  open new PDF file; insert a file name to create the PDF on disk */
				if ($p->begin_document("", "") == 0) {
						die("Error: " . $p->get_errmsg());
				}

				$p->set_info("Creator", "hello.php");
				$p->set_info("Author", "Rainer Schaaf");
				$p->set_info("Title", "Hello world (PHP)!");

				$p->begin_page_ext(595, 842, "");

				$font = $p->load_font("Helvetica-Bold", "winansi", "");

				$p->setfont($font, 24.0);
				$p->set_text_pos(50, 700);
				$p->show("Hello world!");
				$p->continue_text("(says PHP)");
				$p->end_page_ext("");

				$p->end_document("");

				$buf = $p->get_buffer();
				$len = strlen($buf);

				header("Content-type: application/pdf");
				header("Content-Length: $len");
				header("Content-Disposition: inline; filename=hello.pdf");
				print $buf;
				$p->open_file('1.pdf');
		}
		catch (PDFlibException $e) {
				die("PDFlib exception occurred in hello sample:\n" .
						"[" . $e->get_errnum() . "] " . $e->get_apiname() . ": " .
						$e->get_errmsg() . "\n");
		}
		catch (Exception $e) {
				die($e);
		}
		$p = 0;
	}
}
