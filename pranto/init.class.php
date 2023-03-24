<?php

namespace Pranto;

class Init
{
    public function paging($page, $total, $limit)
    {
        if ($total > $limit) {
            if ($page < ceil($total / $limit)) {
                $next = "?" . $this->add_url("page", ($page + 1));
            } else {
                $next = "";
            }

            if ($page > 1) {
                $prev = "?" . $this->add_url("page", ($page - 1));
            } else {
                $prev = "";
            }
            $rt = '<nav aria-label="Page navigation" align="center">
					<p class="text-center">Showing ' . (($page - 1) * $limit) . '-' . (($page - 1) + $limit) . ' of ' . $total . '</p>
	  				<ul class="pagination">
	    			<li>
	      				<a href="' . $prev . '" aria-label="Previous">
	        				<span aria-hidden="true">&laquo;</span>
	      				</a>
	    			</li>';

            for ($i = $page; $i <= (ceil($total / $limit) > 5 ? $page + 4 : ceil($total / $limit)); $i++) {
                if ($page == $i) {
                    $rt .= '<li class="active"><a href="#">' . ($i) . '</a></li>';
                } else {
                    $rt .= '<li><a href="?' . $this->add_url("page", $i) . '">' . ($i) . '</a></li>';
                }
            }

            $rt .= '<li>
				      <a href="' . $next . '" aria-label="Next">
				        <span aria-hidden="true">&raquo;</span>
				      </a>
				    </li>
				  </ul>
					</nav>';
        } else {
            $rt = '<p class="text-center">Showing ' . (($page - 1) * $limit) . '-' . (($page - 1) + $limit) . ' of ' . $total . '</p>';
        }

        global $smarty;
        $smarty->assign("paging", $rt);
    }

    public function paging_admin($page, $total, $limit)
    {
        if ($total > $limit) {
            if ($page < ceil($total / $limit)) {
                $next = "?" . $this->add_url("page", ($page + 1));
            } else {
                $next = "";
            }

            if ($page > 1) {
                $prev = "?" . $this->add_url("page", ($page - 1));
            } else {
                $prev = "";
            }
            echo '<nav aria-label="Page navigation" align="center">
					<p class="text-center">Showing ' . (($page - 1) * $limit) . '-' . (($page - 1) + $limit) . ' of ' . $total . '</p>
	  				<ul class="pagination">
	    			<li>
	      				<a href="' . $prev . '" aria-label="Previous">
	        				<span aria-hidden="true">&laquo;</span>
	      				</a>
	    			</li>';

            for ($i = $page; $i <= (ceil($total / $limit) > 5 ? $page + 4 : ceil($total / $limit)); $i++) {
                if ($page == $i) {
                    echo '<li class="active"><a href="#">' . ($i) . '</a></li>';
                } else {
                    echo '<li><a href="?' . $this->add_url("page", $i) . '">' . ($i) . '</a></li>';
                }
            }

            echo '<li>
				      <a href="' . $next . '" aria-label="Next">
				        <span aria-hidden="true">&raquo;</span>
				      </a>
				    </li>
				  </ul>
					</nav>';
        } else {
            echo '<p class="text-center">Showing ' . (($page - 1) * $limit) . '-' . (($page - 1) + $limit) . ' of ' . $total . '</p>';
        }
    }

    public function mail($mail)
    {
        global $db;
        $Mail = $db->select("mail", $mail, array());
        $mails = $Mail->fetch_assoc();
        return $mails[$mail];

    }


    public function add_url($param, $value)
    {
        $gets = $_GET;
        $gets[$param] = $value;
        return http_build_query($gets);
    }

    /**
     * @param $var
     * @return mixed
     */
    public function settings($var)
    {
        global $db;
        $data = $db->select("settings", $var, array());
        $data = $data->fetch_assoc();
        return $data[$var];
    }

}