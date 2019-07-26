<?php

class Noticias_model extends CI_Model
{

    public $table_noticias = 'front_noticias';
    public $table_categorias = 'front_noticias_categorias';
    public $table_tags = 'front_noticias_tags';
    public $table_tags_fk = 'front_noticias_tags_fk';
    public $table_midias = 'front_media_center_files';

    public function __construct()
    {
        parent::__construct();
    }

    public function apiGet($params = array())
    {
        try {

            if (!empty($params)) {

                $this->db->select("N.id,
                                  N.id_ncategoria,
                                  N.titulo,
                                  N.titulo_alternativo,
                                  N.lead,
                                  N.texto,
                                  N.ntype,
                                  N.fonte,
                                  N.reporter,
                                  N.is_public,
                                  date_format(N.public_date, '%d') AS dia,
                                  date_format(N.public_date, '%m') AS mes,
                                  CASE MONTHNAME(N.public_date)
                                                WHEN 'January'   THEN 'Janeiro'
                                                WHEN 'February'  THEN 'Fevereiro'
                                                WHEN 'March'     THEN 'Março'
                                                WHEN 'April'     THEN 'Abril'
                                                WHEN 'May'       THEN 'Maio'
                                                WHEN 'June'      THEN 'Junho'
                                                WHEN 'July'      THEN 'Julho'
                                                WHEN 'August'    THEN 'Agosto'
                                                WHEN 'September' THEN 'Setembro'
                                                WHEN 'November'  THEN 'Novembro'
                                                WHEN 'December'  THEN 'Dezembro'
                                                END as mesExt,
                                  date_format(N.public_date, '%Y') AS ano,
                                  N.public_date,
                                  date_format(N.public_date, '%M,  %d/%Y') AS publicado,
                                  N.url_slug,
                                  C.titulo AS categoria,
                                  C.slug_editoria,
                                  M.arquivo");
                $this->db->from("$this->table_noticias  N");
                $this->db->join("$this->table_categorias C", "C.id = N.id_ncategoria", "LEFT");
                $this->db->join("$this->table_midias M", "N.id = M.id_referencia AND M.arquivo_tipo ='imagem' AND M.modulo = 'noticia' AND M.is_gallery = 0", "LEFT");
                $this->db->where('N.is_public', 1);

                //-> VERIFICA A EXISTÊNCIA DO PARÂMETRO url_slug
                if (array_key_exists("url_slug", $params)) {
                    $this->db->where('url_slug', $params['url_slug']);
                }

                //-> VERIFICA A EXISTÊNCIA DO PARÂMETRO type
                if (array_key_exists("type", $params)):
                    $this->db->where('N.ntype', $params['type']);
                endif;

                //-> ORDENAÇÃO DOS REGISTROS
                $this->db->order_by("N.public_date DESC, N.id DESC");

                //-> VERIFICA A EXISTÊNCIA DO PARÂMETRO limit
                if (array_key_exists("limit", $params)):
                    $this->db->limit($params['limit']);
                endif;

                $query = $this->db->get();

                //-> VERIFICA A EXISTÊNCIA DO PARÂMETRO limit e type
                if (array_key_exists("limit", $params) && array_key_exists("type", $params)):
                    return $query->result_array();
                else:
                    return $query->row_array();
                endif;

            } else {
                //   $query = $this->db->order_by('id', 'desc')->get($this->table_noticias);

                $this->db->select("N.id,
                                    N.id_ncategoria,
                                    N.titulo,
                                    N.titulo_alternativo,
                                    N.lead,
                                    N.texto,
                                    N.ntype,
                                    N.fonte,
                                    N.reporter,
                                    N.is_public,
                                    date_format(N.public_date, '%d') AS dia,
                                    date_format(N.public_date, '%m') AS mes,
                                    CASE MONTHNAME(N.public_date)
                                                  WHEN 'January'   THEN 'Janeiro'
                                                  WHEN 'February'  THEN 'Fevereiro'
                                                  WHEN 'March'     THEN 'Março'
                                                  WHEN 'April'     THEN 'Abril'
                                                  WHEN 'May'       THEN 'Maio'
                                                  WHEN 'June'      THEN 'Junho'
                                                  WHEN 'July'      THEN 'Julho'
                                                  WHEN 'August'    THEN 'Agosto'
                                                  WHEN 'September' THEN 'Setembro'
                                                  WHEN 'November'  THEN 'Novembro'
                                                  WHEN 'December'  THEN 'Dezembro'
                                                  END as mesExt,
                                    date_format(N.public_date, '%Y') AS ano,
                                    N.public_date,
                                    date_format(N.public_date, '%M,  %d/%Y') AS publicado,
                                    N.url_slug,
                                    C.titulo AS categoria,
                                    C.slug_editoria,
                                    M.arquivo");
                $this->db->from("$this->table_noticias  N");
                $this->db->join("$this->table_categorias C", "C.id = N.id_ncategoria", "LEFT");
                $this->db->join("$this->table_midias M", "N.id = M.id_referencia AND M.arquivo_tipo ='imagem' AND M.modulo = 'noticia' AND M.is_gallery = 0", "LEFT");

                $query = $this->db->get();
                return $query->result_array();

            }

        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function conta_registros()
    {
        try {
            return $this->db->count_all("$this->table_noticias");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAll_noticias($limit, $type = false)
    {
        try {
            $this->db->select("N.id,
                              N.id_ncategoria,
                              N.titulo,
                              N.titulo_alternativo,
                              N.lead,
                              N.ntype,
                              N.reporter,
                              N.is_public,
                              date_format(N.public_date, '%d') AS dia,
                              date_format(N.public_date, '%m') AS mes,
                              CASE MONTHNAME(N.public_date)
                                            WHEN 'January'   THEN 'Janeiro'
                                            WHEN 'February'  THEN 'Fevereiro'
                                            WHEN 'March'     THEN 'Março'
                                            WHEN 'April'     THEN 'Abril'
                                            WHEN 'May'       THEN 'Maio'
                                            WHEN 'June'      THEN 'Junho'
                                            WHEN 'July'      THEN 'Julho'
                                            WHEN 'August'    THEN 'Agosto'
                                            WHEN 'September' THEN 'Setembro'
                                            WHEN 'November'  THEN 'Novembro'
                                            WHEN 'December'  THEN 'Dezembro'
                                            END as mesExt,
                              date_format(N.public_date, '%Y') AS ano,
                              N.public_date,
                              date_format(N.public_date, '%M,  %d/%Y') AS publicado,
                              N.url_slug,
                              C.titulo AS categoria,
                              C.slug_editoria");
            $this->db->from("$this->table_noticias  N");
            $this->db->join("$this->table_categorias C", "C.id = N.id_ncategoria", "LEFT");
            $this->db->where('N.is_public', 1);
            if ($type):
                $this->db->where('N.ntype', "$type");
            endif;
            $this->db->order_by("N.public_date DESC, N.id DESC");
            $this->db->limit($limit);
            $query = $this->db->get();
//print $this->db->last_query();
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                return $data;
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get_noticias($limit, $type)
    {
        try {
            $query = $this->db->query("SELECT N.id,
        N.id_ncategoria,
        N.titulo,
        N.titulo_alternativo,
        N.lead,
        N.ntype,
        N.reporter,
        N.is_public,
        date_format(N.public_date, '%m') AS mes,
        date_format(N.public_date, '%Y') AS ano,
        N.public_date,
        date_format(N.public_date, '%d/%m/%Y %H:%i:%s') AS publicado,
        N.url_slug,
        C.titulo AS categoria
        FROM " . $this->table_noticias . " N
        LEFT JOIN " . $this->table_categorias . " C ON(C.id = N.id_ncategoria)
        WHERE N.is_public = 1 AND N.ntype = '$type'
        ORDER BY N.public_date DESC
        LIMIT $limit");
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                return $data;
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function record_count_noticias($search_string)
    {
        try {
            if ($search_string == "NIL") {
                $search_string = "";
            }

            if (!empty($search_string)) {
                $this->db->where("titulo LIKE '%" . trataBusca($search_string) . "%' ESCAPE '!' OR lead LIKE '%" . trataBusca($search_string) . "%' ESCAPE '!' OR texto LIKE '%" . trataBusca($search_string) . "%' ESCAPE '!'");
            }
            return $this->db->count_all_results("$this->table_noticias");

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get_noticias_search($limit, $start, $search_string = null, $is_deleted = false)
    {
        try {
            if ($search_string == "NIL") {
                $search_string = "";
            }

            if (!empty($search_string)) {
                $WHERE = "WHERE N.titulo LIKE '%" . $search_string . "%'  ESCAPE '!' OR N.lead LIKE '%" . $search_string . "%'  ESCAPE '!' OR N.texto LIKE '%" . $search_string . "%'  ESCAPE '!'  ";
            } else {
                $WHERE = '';
            }

            $query = $this->db->query("SELECT N.id,
          N.id_ncategoria,
          N.titulo,
          N.titulo_alternativo,
          N.lead,
          N.ntype,
          N.reporter,
          N.is_public,
          date_format(N.public_date, '%m') AS mes,
          date_format(N.public_date, '%Y') AS ano,
          N.public_date,
          date_format(N.public_date, '%d/%m/%Y %H:%i:%s') AS publicado,
          N.url_slug,
          C.titulo AS categoria
          FROM " . $this->table_noticias . " N
          INNER JOIN " . $this->table_categorias . " C ON(C.id = N.id_ncategoria)
          $WHERE
          ORDER BY N.id DESC
          LIMIT $start, $limit");
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                return $data;
            }
            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update_counter_views($slug)
    {

        $this->db->where('url_slug', urldecode($slug));
        $this->db->select('views');
        $count = $this->db->get($this->table_noticias)->row();

        $this->db->where('url_slug', urldecode($slug));
        $this->db->set('views', ($count->views + 1));
        $this->db->update($this->table_noticias);
    }

    public function read_materia($slug)
    {
        try {
            $query = $this->db->query("SELECT N.id,
            N.id_ncategoria,
            N.titulo,
            N.titulo_alternativo,
            N.lead,
            N.texto,
            N.ntype,
            N.reporter,
            N.is_public,
            date_format(N.public_date, '%m') AS mes,
            date_format(N.public_date, '%Y') AS ano,
            N.public_date,
            date_format(N.public_date, '%d/%m/%Y %H:%i:%s') AS publicado,
            N.url_slug,
            C.titulo AS categoria,
            C.slug_editoria
            FROM " . $this->table_noticias . " N
            LEFT JOIN " . $this->table_categorias . " C ON(C.id = N.id_ncategoria)
            WHERE N.is_public = 1 AND N.url_slug = '$slug'");
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                return $data;
            }
            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getCategorias()
    {
        try {
            $this->db->select('*');
            $this->db->from("$this->table_categorias");
            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                foreach ($q->result() as $row):
                    $data[] = $row;
                endforeach;
                return $data;
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function fkMedias($params = array())
    {
        //  var_dump($params);
        //$id, $arquivo_tipo, $modulo, $is_gallery
        try {
            $this->db->select("*");
            if (array_key_exists("id_referencia", $params)) {
                $this->db->where('id_referencia', $params['id_referencia']);
            }
            if (array_key_exists("arquivo_tipo", $params)) {
                $this->db->where('arquivo_tipo', $params['arquivo_tipo']);
            }
            if (array_key_exists("modulo", $params)) {
                $this->db->where('modulo', $params['modulo']);
            }
            if (array_key_exists("is_gallery", $params)) {
                $this->db->where('is_gallery', $params['is_gallery']);
            }
            /*
            $this->db->where('id_referencia', $id);
            $this->db->where('arquivo_tipo', $arquivo_tipo);
            $this->db->where('modulo', $modulo);
            $this->db->where('is_gallery', $is_gallery);*/
            $query = $this->db->get($this->table_midias);

            return $query->result_array();
//print $this->db->last_query();
            /*if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                return $data;
            }*/
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function fkTags($id)
    {
        try {
            $this->db->select("t.id, t.tag,t.slug_tag");
            $this->db->join("$this->table_tags_fk as fkt", 't.id = fkt.id_tag', 'inner');
            $this->db->where('fkt.id_noticia', $id);
            $query = $this->db->get("$this->table_tags as t");
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getNewsRel($id, $idCategoria, $limit = 10)
    {
        try {

            $query = $this->db->query("SELECT N.id,
              N.id_ncategoria,
              N.titulo,
              N.titulo_alternativo,
              N.lead,
              N.ntype,
              N.reporter,
              N.is_public,
              date_format(N.public_date, '%m') AS mes,
              date_format(N.public_date, '%Y') AS ano,
              N.public_date,
              date_format(N.public_date, '%d/%m/%Y %H:%i') AS publicado,
              N.url_slug,
              C.titulo AS categoria
              FROM " . $this->table_noticias . " N
              INNER JOIN " . $this->table_categorias . " C ON(C.id = N.id_ncategoria)
              WHERE N.is_public = 1 AND N.id_ncategoria = '$idCategoria' AND N.id <> '$id'
              ORDER BY N.public_date DESC
              LIMIT $limit");

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function categorias_by_slug($slug)
    {
        try {
            $this->db->select('*');
            $this->db->from("$this->table_categorias");
            $this->db->where('slug_editoria', "$slug");
            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                foreach ($q->result() as $row):
                    $data[] = $row;
                endforeach;

                return $data;
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function record_count_materias_editoria($search_string = null, $id)
    {
        try {
            if ($search_string == "NIL") {
                $search_string = "";
            }

            $this->db->from("$this->table_noticias N");
            $this->db->join("$this->table_categorias C", 'N.id_ncategoria = C.id', 'INNER');
            $this->db->where('N.id_ncategoria', $id);
            $this->db->where('N.is_public', 1);
            if ($search_string):
                $this->db->where("N.titulo LIKE '%" . $search_string . "%' ESCAPE '!' OR N.texto LIKE '%" . $search_string . "%' ESCAPE '!'");
            endif;
            $q = $this->db->get();

            return $q->num_rows();

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function get_materias_by_categoria($limit, $start, $categoria)
    {
        try {

            $query = $this->db->query("SELECT N.id,
                N.id_ncategoria,
                N.titulo,
                N.titulo_alternativo,
                N.lead,
                N.ntype,
                N.reporter,
                N.is_public,
                date_format(N.public_date, '%m') AS mes,
                date_format(N.public_date, '%Y') AS ano,
                N.public_date,
                date_format(N.public_date, '%d/%m/%Y %H:%i:%s') AS publicado,
                N.url_slug,
                C.titulo AS categoria
                FROM " . $this->table_noticias . " N
                INNER JOIN " . $this->table_categorias . " C ON(C.id = N.id_ncategoria)
                WHERE N.id_ncategoria = '$categoria'
                ORDER BY N.id DESC
                LIMIT $start, $limit");
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                return $data;
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function tag_by_slug($slug)
    {
        try {
            $this->db->select('*');
            $this->db->from("$this->table_tags");
            $this->db->where('slug_tag', "$slug");
            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                foreach ($q->result() as $row):
                    $data[] = $row;
                endforeach;

                return $data;
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function get_materias_by_tag($limit, $start, $tag)
    {
        try {
            $query = $this->db->query("SELECT N.id,
                  N.id_ncategoria,
                  N.titulo,
                  N.titulo_alternativo,
                  N.lead,
                  N.ntype,
                  N.reporter,
                  N.is_public,
                  date_format(N.public_date, '%m') AS mes,
                  date_format(N.public_date, '%Y') AS ano,
                  N.public_date,
                  date_format(N.public_date, '%d/%m/%Y %H:%i:%s') AS publicado,
                  N.url_slug,
                  T.tag,
                  C.titulo AS categoria
                  FROM " . $this->table_noticias . " N
                  INNER JOIN " . $this->table_categorias . " C ON(C.id = N.id_ncategoria)
                  INNER JOIN " . $this->table_tags_fk . " TFK ON(TFK.id_noticia = N.id)
                  INNER JOIN " . $this->table_tags . " T ON(TFK.id_tag = T.id)
                  WHERE TFK.id_tag = '$tag'
                  ORDER BY N.public_date DESC
                  LIMIT $start, $limit");
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[] = $row;
                }
                return $data;
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function record_count_materias_tag($id)
    {
        try {
            $this->db->from("$this->table_noticias N");
            $this->db->join("$this->table_tags_fk TFK", "TFK.id_noticia = N.id", "INNER");
            $this->db->join("$this->table_tags T", "TFK.id_tag = T.id", "INNER");
            $this->db->where('TFK.id_tag', $id);
            $this->db->where('N.is_public', 1);

            $q = $this->db->get();

            return $q->num_rows();

        } catch (Exception $e) {
            throw $e;
        }
    }

}
