<?php

include("../models/Reverseproxies.model.php");

class ReverseProxyController extends LeapController
{
  public function index()
  {

    $domaininfos = file_get_contents("../domains.txt");
    $domaininfos = explode("\n", $domaininfos);
    foreach ($domaininfos as $domaininfo) {
      $domaininfo = explode(";", $domaininfo);
      $domain = $domaininfo[0];
      if ($domain == '') {
        continue;
      }
      $date = $domaininfo[1];
      $data = Reverseproxies::WhereOne("server_name = '" . $domain . "'");
      if ($data) {
        $data->acme_valid_until = $date;
        $data->save();
      }
    }


    $data = $this->db->query("SELECT * FROM reverseproxies r INNER JOIN users u ON r.idusers = u.idusers");
    $this->view->data = $data;




    $this->view->render('reverse_proxy/index');
  }

  public function edit($id = null)
  {
    $data = new Reverseproxies();
    $data->idusers = $this->session->getUserId();
    if ($this->request->isPost()) {
      if ($id) {
        $data = Reverseproxies::WhereOne("idreverseproxies=" . $id);
        if (!$data) {
          echo "No data found";
          die();
        }
      }
      $data->loadFromRequest($this->request);
      $data->save();
      $this->view->flash("Reverse Proxy updated successfully");
      $this->redirect("/manage");
      return;
    }

    if ($id) {
      $data = Reverseproxies::WhereOne("idreverseproxies=" . $id);
      if (!$data) {
        echo "No data found";
        die();
      }
    }
    $this->view->data = $data;
    $this->view->render('/reverse_proxy/edit');
  }

  public function delete(int $id)
  {
    Reverseproxies::delete($id);
    $this->redirect("/manage");
  }

  public function deploy(int $id)
  {
    $data = Reverseproxies::WhereOne("idreverseproxies=" . $id);
    if (!$data) {
      echo "No data found";
      die();
    }


    $config = <<<NGINX
server {
    server_name {$data->server_name};
    access_log /var/log/nginx/{$data->server_name}.access.log;
    error_log /var/log/nginx/{$data->server_name}.error.log;
    location / {
          client_max_body_size 500M;
          proxy_pass http://{$data->target_address}:{$data->target_port}/;
          proxy_set_header Host \$http_host;
          proxy_set_header X-Forwarded-Host \$http_host;
          proxy_set_header X-Real-IP \$remote_addr;
          proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
          proxy_set_header X-Forwarded-Proto \$scheme;
    }
    listen [::]:80;
    listen 80; 
}
NGINX;
    foreach (glob("/etc/nginx/sites-enabled/*") as $file) {
      echo $file . "<br/>";
    }
    flush();
    // TODO: Implement deploy
    // 1) Create a new nginx config file for port 80
    // 2) Reload nginx
    // 3) run certbox for the domain to get the certificate
    // 4) restart nginx


    $this->view->flash("Reverse Proxy deployed successfully");
    $this->redirect("/manage");
  }
}
