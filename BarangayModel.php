<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarangayModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Model function - BarangayModel.php
    public function insert_resident($data)
    {
        $this->db->insert('residents', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id > 0;
    }


    // Model function - BarangayModel.php
    public function get_residents()
    {
        return $this->db->get('residents')->result(); // Returns objects instead of arrays
    }


    // Model function - BarangayModel.php
    public function get_resident_by_id($id)
    {
        $query = $this->db->get_where('residents', array('resident_ID' => $id));
        return $query->row();
    }

    public function update_resident($id, $data)
    {
        $this->db->where('resident_ID', $id);
        return $this->db->update('residents', $data);
    }

    public function delete_resident($id)
    {
        $this->db->where('resident_ID', $id);
        return $this->db->delete('residents');
    }

    public function validate_user($username, $password)
    {
        // Query the users table once
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            $user = $query->row_array();

            // Determine user type based on role column or another indicator in your users table
            // Assuming you have a 'role' column in your users table
            $user['user_type'] = $user['role']; // Or however you distinguish between admin and secretary

            return $user;
        }

        return false; // No valid user found
    }

    public function insert_household($data)
    {
        $this->db->insert('household', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id > 0;
    }

    // In BarangayModel
    // Get household by household number
    public function get_household_by_number($household_number)
    {
        $this->db->where('household_number', $household_number);
        $query = $this->db->get('household');
        return $query->row();
    }

    // Update household data
    public function update_household($household_number, $data)
    {
        $this->db->where('household_number', $household_number);
        $this->db->update('household', $data);
        return $this->db->affected_rows() > 0;
    }


    public function insert_secretary($data)
    {
        $this->db->insert('barangay_secretary', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id > 0;
    }

    public function get_secretaries()
    {
        return $this->db->get('barangay_secretary')->result(); // Returns objects instead of arrays
    }

    // Update secretary data
    public function update_secretary($secretary_id, $data)
    {
        $this->db->where('secretary_ID', $secretary_id);
        $this->db->update('barangay_secretary', $data);
        return $this->db->affected_rows() > 0;
    }

    // Delete secretary by ID
    public function delete_secretary($secretary_id)
    {
        $this->db->where('secretary_ID', $secretary_id);
        return $this->db->delete('barangay_secretary');
    }

    // Edit secretary by ID
    public function get_secretary_by_id($id)
    {
        $query = $this->db->get_where('secretary_profile', array('user_ID' => $id));
        return $query->row();
    }

    public function get_family_count()
    {
        $this->db->select('count(distinct household_number) as count');
        $this->db->from('residents');
        $query = $this->db->get();
        return $query->row()->count;
    }

    public function get_genders()
    {
        $this->db->select('gender, COUNT(*) as count');
        $this->db->from('residents');
        $this->db->where('gender IS NOT NULL');
        $this->db->group_by('gender');

        $query = $this->db->get();

        if ($query === FALSE) {
            log_message('error', 'Gender count query failed: ' . $this->db->error()['message']);
            return [];
        }

        return $query->result();
    }


    // Get all families
    public function get_families($familyName = '', $householdNumber = '')
    {
        $this->db->select('household_number, lastname, firstname, gender, occupation');
        $this->db->from('residents');
        $this->db->where('resident_type !=', 'Temporary '); // Don't include temporary residents

        // Apply filters based on input
        if (!empty($familyName)) {
            $this->db->like('lastname', $familyName);  // Use LIKE for partial matching
        }

        if (!empty($householdNumber)) {
            $this->db->like('household_number', $householdNumber);
        }

        $query = $this->db->get();
        return $query->result();
    }

    // This method is used by the reports page for the dropdown
    public function get_barangays()
    {
        // Ensure this table has barangay_ID and barangay_name columns
        // Or adjust the query to select the correct columns
        $this->db->select('barangay_ID, barangay_name'); // Explicitly select needed columns
        $this->db->from('barangay'); // Assuming 'barangay' is your barangay list table
        $this->db->order_by('barangay_name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    // --- NEW METHODS FOR REPORTING ---

    private function _apply_barangay_filter($barangay_names_array)
    {
        if (!empty($barangay_names_array) && !in_array('all', $barangay_names_array)) {
            // Ensure that barangay_name exists in the residents table
            // If your residents table uses barangay_ID, you might need a join or a subquery
            // For now, assuming barangay_name is directly in residents table
            $this->db->where_in('residents.barangay_name', $barangay_names_array);
        }
        // If 'all' is in the array or the array is empty, no specific barangay filter is applied
    }


    public function get_residents_per_barangay()
    {
        $this->db->select('barangay_name, COUNT(*) as resident_count');
        $this->db->from('residents'); // Make sure 'residents' is your actual table name
        $this->db->group_by('barangay_name');
        $this->db->order_by('barangay_name', 'ASC'); // Optional: order by name
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_report($data)
    {
        if ($this->db->insert('contact_support', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_sec_reports()
    {
        $this->db->select('barangay_secretary.username, barangay_secretary.barangay_name, contact_support.message_content, contact_support.created_at');
        $this->db->from('contact_support');
        $this->db->join('barangay_secretary', 'barangay_secretary.secretary_ID = contact_support.secretary_ID', 'left');
        $this->db->order_by('contact_support.created_at', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }

    /*     public function get_age_group_data($barangay_filter = ['all'])
        {
            // Define age groups
            $age_groups = [
                '0-4' => ['min' => 0, 'max' => 4],
                '5-12' => ['min' => 5, 'max' => 12],
                '13-19' => ['min' => 13, 'max' => 19],
                '20-35' => ['min' => 20, 'max' => 35],
                '36-59' => ['min' => 36, 'max' => 59],
                '60+' => ['min' => 60, 'max' => 999]
            ];


            $result = [];

            // If 'all' is selected, we'll get data for all barangays combined
            if (in_array('all', $barangay_filter)) {
                $barangay_clause = "";
            } else {
                $barangay_clause = "AND r.barangay_name IN ('" . implode("','", $barangay_filter) . "')";
            }

            foreach ($age_groups as $group_name => $range) {
                $min_age = $range['min'];
                $max_age = $range['max'];

                // Using YEAR() function to correctly calculate age from birth_date
                $query = $this->db->query("
                SELECT COUNT(*) as count
                FROM residents r
                WHERE TIMESTAMPDIFF(YEAR, r.birth_date, CURDATE()) >= $min_age
                AND TIMESTAMPDIFF(YEAR, r.birth_date, CURDATE()) <= $max_age
                $barangay_clause
            ");

                if ($query === FALSE) {
                    // Handle query error - this would catch syntax errors
                    $error = $this->db->error();
                    log_message('error', 'Database error: ' . $error['message']);
                    continue; // Skip this age group if query fails
                }

                $row = $query->row();
                $result[] = [
                    'Age Group' => $group_name,
                    'Count' => (int) $row->count
                ];
            }

            return $result;
        } */

    /*   public function get_report_family_count_data($barangay_filter = ['all'])
      {
          $this->db->select('barangay_name, COUNT(DISTINCT household_number) as family_count');
          $this->db->from('residents');
          $this->db->where('household_number !=', ''); // Exclude empty household numbers

          if (!in_array('all', $barangay_filter)) {
              $this->db->where_in('barangay_name', $barangay_filter);
          }

          $this->db->group_by('barangay_name');
          $query = $this->db->get();

          $result = [];

          // Check if we got any results
          if ($query->num_rows() > 0) {
              foreach ($query->result() as $row) {
                  $result[] = [
                      'Barangay Name' => $row->barangay_name,
                      'Family Count' => (int) $row->family_count
                  ];
              }
          }

          // If using 'all' and there are no results for some barangays, we need to get a list of all barangays
          if (in_array('all', $barangay_filter)) {
              $existing_barangays = array_column($result, 'Barangay Name');

              // Get all barangays from the database
              $barangay_query = $this->db->select('barangay_name')->from('barangays')->get();

              foreach ($barangay_query->result() as $barangay) {
                  if (!in_array($barangay->barangay_name, $existing_barangays)) {
                      $result[] = [
                          'Barangay Name' => $barangay->barangay_name,
                          'Family Count' => 0 // Show 0 count instead of 1 for barangays with no families
                      ];
                  }
              }
          }

          return $result;
      } */

    /*  public function get_population_summary_data($barangay_filter = ['all'])
     {
         $this->db->select('barangay_name, COUNT(*) as total_population');
         $this->db->from('residents');

         if (!in_array('all', $barangay_filter)) {
             $this->db->where_in('barangay_name', $barangay_filter);
         }

         $this->db->group_by('barangay_name');
         $query = $this->db->get();

         // Add this check before trying to access result()
         if ($query === FALSE) {
             // Log the error
             log_message('error', 'Population summary query failed: ' . $this->db->error()['message']);
             return []; // Return empty array instead of failing
         }

         $result = [];

         // Check if we got any results
         if ($query->num_rows() > 0) {
             foreach ($query->result() as $row) {
                 $result[] = [
                     'Barangay Name' => $row->barangay_name,
                     'Total Population' => (int) $row->total_population
                 ];
             }
         }

         // If using 'all' and there are no results for some barangays, we need to get a list of all barangays
         if (in_array('all', $barangay_filter)) {
             $existing_barangays = array_column($result, 'Barangay Name');

             // Get all barangays from the database
             $barangay_query = $this->db->select('barangay_name')->from('barangays')->get();

             foreach ($barangay_query->result() as $barangay) {
                 if (!in_array($barangay->barangay_name, $existing_barangays)) {
                     $result[] = [
                         'Barangay Name' => $barangay->barangay_name,
                         'Total Population' => 0 // Show 0 count instead of default for barangays with no residents
                     ];
                 }
             }
         }

         return $result;
     } */

    /*   public function get_gender_distribution_data($barangay_filter = ['all'])
      {
          $this->db->select('barangay_name, gender, COUNT(*) as count');
          $this->db->from('residents');

          if (!in_array('all', $barangay_filter)) {
              $this->db->where_in('barangay_name', $barangay_filter);
          }

          $this->db->group_by('barangay_name, gender');
          $query = $this->db->get();

          // Add this check before trying to access result()
          if ($query === FALSE) {
              // Log the error
              log_message('error', 'Gender distribution query failed: ' . $this->db->error()['message']);
              return []; // Return empty array instead of failing
          }

          $result = [];

          // Check if we got any results
          if ($query->num_rows() > 0) {
              foreach ($query->result() as $row) {
                  $result[] = [
                      'Barangay Name' => $row->barangay_name,
                      'Gender' => $row->gender,
                      'Count' => (int) $row->count
                  ];
              }
          }

          // If using 'all', make sure both Male and Female are present for each barangay
          if (in_array('all', $barangay_filter)) {
              // Get all barangays
              $barangay_query = $this->db->select('barangay_name')->from('barangays')->get();
              $all_barangays = [];

              foreach ($barangay_query->result() as $row) {
                  $all_barangays[] = $row->barangay_name;
              }

              // For each barangay, ensure there's a count for both genders
              foreach ($all_barangays as $barangay) {
                  $has_male = false;
                  $has_female = false;

                  foreach ($result as $item) {
                      if ($item['Barangay Name'] === $barangay && $item['Gender'] === 'Male') {
                          $has_male = true;
                      }
                      if ($item['Barangay Name'] === $barangay && $item['Gender'] === 'Female') {
                          $has_female = true;
                      }
                  }

                  if (!$has_male) {
                      $result[] = [
                          'Barangay Name' => $barangay,
                          'Gender' => 'Male',
                          'Count' => 0
                      ];
                  }

                  if (!$has_female) {
                      $result[] = [
                          'Barangay Name' => $barangay,
                          'Gender' => 'Female',
                          'Count' => 0
                      ];
                  }
              }
          }

          return $result;
      } */
    public function get_all_barangay_names()
    {
        $query = $this->db->select('barangay_name')
            ->from('barangays')
            ->get();

        if ($query === FALSE) {
            log_message('error', 'Failed to get barangay names: ' . $this->db->error()['message']);
            return [];
        }

        $barangays = [];
        foreach ($query->result() as $row) {
            $barangays[] = $row->barangay_name;
        }

        return $barangays;
    }

    /**
     * Get population summary data (total residents per barangay)
     * 
     * @param array $barangay_filter List of barangays to filter by, or ['all']
     * @return array Formatted result with barangay names and population counts
     */
    public function get_population_summary_data($barangay_filter = ['all'])
    {
        $this->db->reset_query();
        $this->db->select('barangay_name, COUNT(*) as total_population');
        $this->db->from('residents');

        if (!in_array('all', $barangay_filter)) {
            $this->db->where_in('barangay_name', $barangay_filter);
        }

        $this->db->group_by('barangay_name');

        try {
            $query = $this->db->get();

            if ($query === FALSE) {
                log_message('error', 'Population query failed: ' . $this->db->error()['message']);
                return [];
            }

            $result = [];
            foreach ($query->result() as $row) {
                $result[] = [
                    'Barangay Name' => $row->barangay_name,
                    'Total Population' => (int) $row->total_population
                ];
            }

            // If 'all' barangays selected, ensure all barangays are included (even those with 0 population)
            if (in_array('all', $barangay_filter)) {
                $this->_ensure_all_barangays_included($result, 'Barangay Name', ['Total Population' => 0]);
            }

            return $result;

        } catch (Exception $e) {
            log_message('error', 'Exception in population query: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get gender distribution data (male/female counts per barangay)
     * 
     * @param array $barangay_filter List of barangays to filter by, or ['all']
     * @return array Formatted result with barangay names, genders and counts
     */
    public function get_gender_distribution_data($barangay_filter = ['all'])
    {
        $this->db->reset_query();
        $this->db->select('barangay_name, gender, COUNT(*) as count');
        $this->db->from('residents');

        if (!in_array('all', $barangay_filter)) {
            $this->db->where_in('barangay_name', $barangay_filter);
        }

        $this->db->group_by('barangay_name, gender');

        try {
            $query = $this->db->get();

            if ($query === FALSE) {
                log_message('error', 'Gender query failed: ' . $this->db->error()['message']);
                return [];
            }

            $result = [];
            foreach ($query->result() as $row) {
                $result[] = [
                    'Barangay Name' => $row->barangay_name,
                    'Gender' => $row->gender,
                    'Count' => (int) $row->count
                ];
            }

            // Ensure each barangay has both Male and Female entries
            if (!empty($result)) {
                $this->_ensure_gender_distribution_complete($result);
            }

            return $result;

        } catch (Exception $e) {
            log_message('error', 'Exception in gender query: ' . $e->getMessage());
            return [];
        }
    }

    public function get_report_family_count_data($barangay_filter = ['all'])
    {
        $this->db->reset_query();
        // Count distinct household numbers
        $this->db->select('barangay_name, COUNT(DISTINCT household_number) as family_count');
        $this->db->from('residents');
        $this->db->where('household_number !=', ''); // Exclude empty household numbers

        if (!in_array('all', $barangay_filter)) {
            $this->db->where_in('barangay_name', $barangay_filter);
        }

        $this->db->group_by('barangay_name');

        try {
            $query = $this->db->get();

            if ($query === FALSE) {
                log_message('error', 'Family count query failed: ' . $this->db->error()['message']);
                return [];
            }

            $result = [];
            foreach ($query->result() as $row) {
                $result[] = [
                    'Barangay Name' => $row->barangay_name,
                    'Family Count' => (int) $row->family_count
                ];
            }

            // If 'all' barangays selected, ensure all barangays are included
            if (in_array('all', $barangay_filter)) {
                $this->_ensure_all_barangays_included($result, 'Barangay Name', ['Family Count' => 0]);
            }

            return $result;

        } catch (Exception $e) {
            log_message('error', 'Exception in family count query: ' . $e->getMessage());
            return [];
        }
    }
    /**
     * Get age group distribution data
     * 
     * @param array $barangay_filter List of barangays to filter by, or ['all']
     * @return array Formatted result with age groups and counts
     */
    /**
     * Get age group distribution data with descriptive labels
     * 
     * @param array $barangay_filter List of barangays to filter by, or ['all']
     * @return array Formatted result with age groups and counts
     */
    public function get_age_group_data($barangay_filter = ['all'])
    {
        // Define age groups with descriptive labels
        $age_groups = [
            'Infants (0-4)' => ['min' => 0, 'max' => 4],
            'Children (5-12)' => ['min' => 5, 'max' => 12],
            'Teenagers (13-19)' => ['min' => 13, 'max' => 19],
            'Young Adults (20-35)' => ['min' => 20, 'max' => 35],
            'Middle Aged (36-59)' => ['min' => 36, 'max' => 59],
            'Seniors (60+)' => ['min' => 60, 'max' => 999]
        ];

        $result = [];

        try {
            // If 'all' is selected, we'll get data for all barangays combined
            if (in_array('all', $barangay_filter)) {
                $where_clause = "";
            } else {
                $escaped_barangays = array_map(function ($name) {
                    return $this->db->escape($name);
                }, $barangay_filter);

                $where_clause = "AND r.barangay_name IN (" . implode(',', $escaped_barangays) . ")";
            }

            foreach ($age_groups as $group_name => $range) {
                $min_age = $range['min'];
                $max_age = $range['max'];

                // Using parameterized query to prevent SQL injection
                $sql = "
                SELECT COUNT(*) as count
                FROM residents r
                WHERE TIMESTAMPDIFF(YEAR, r.birth_date, CURDATE()) >= ?
                AND TIMESTAMPDIFF(YEAR, r.birth_date, CURDATE()) <= ?
                {$where_clause}
            ";

                $query = $this->db->query($sql, [$min_age, $max_age]);

                if ($query === FALSE) {
                    $error = $this->db->error();
                    log_message('error', 'Age group query failed: ' . $error['message'] . ' - SQL: ' . $this->db->last_query());
                    continue; // Skip this age group if query fails
                }

                $row = $query->row();
                $result[] = [
                    'Age Group' => $group_name,
                    'Count' => (int) $row->count
                ];
            }

            return $result;

        } catch (Exception $e) {
            log_message('error', 'Exception in age group query: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Helper method to ensure all barangays are included in the result
     * 
     * @param array &$result Reference to the result array
     * @param string $key_field The field name that contains barangay names
     * @param array $default_values Default values for barangays with no data
     */
    private function _ensure_all_barangays_included(&$result, $key_field, $default_values)
    {
        $this->db->reset_query();
        $barangay_query = $this->db->select('barangay_name')
            ->from('barangays')
            ->get();

        if ($barangay_query === FALSE) {
            log_message('error', 'Failed to get barangay list: ' . $this->db->error()['message']);
            return;
        }

        // Get existing barangays from the result
        $existing_barangays = array_column($result, $key_field);

        // Add missing barangays with default values
        foreach ($barangay_query->result() as $barangay) {
            if (!in_array($barangay->barangay_name, $existing_barangays)) {
                $new_row = array_merge(
                    [$key_field => $barangay->barangay_name],
                    $default_values
                );
                $result[] = $new_row;
            }
        }
    }

    /**
     * Helper method to ensure both Male and Female are present for each barangay
     * 
     * @param array &$result Reference to the gender distribution result array
     */
    private function _ensure_gender_distribution_complete(&$result)
    {
        $this->db->reset_query();
        $barangay_query = $this->db->select('barangay_name')
            ->from('barangays')
            ->get();

        if ($barangay_query === FALSE) {
            log_message('error', 'Failed to get barangay list for gender distribution: ' . $this->db->error()['message']);
            return;
        }

        $barangay_genders = [];

        // Index existing data by barangay and gender
        foreach ($result as $item) {
            $key = $item['Barangay Name'] . '|' . $item['Gender'];
            $barangay_genders[$key] = true;
        }

        // Check each barangay for missing genders
        foreach ($barangay_query->result() as $barangay) {
            foreach (['Male', 'Female'] as $gender) {
                $key = $barangay->barangay_name . '|' . $gender;
                if (!isset($barangay_genders[$key])) {
                    $result[] = [
                        'Barangay Name' => $barangay->barangay_name,
                        'Gender' => $gender,
                        'Count' => 0
                    ];
                }
            }
        }
    }

    public function get_resident_type_data($barangay_filter = ['all'])
    {
        $this->db->reset_query();
        $this->db->select('barangay_name, resident_type, COUNT(*) as count');
        $this->db->from('residents');

        if (!in_array('all', $barangay_filter)) {
            $this->db->where_in('barangay_name', $barangay_filter);
        }

        // Filter to only include Permanent and Temporary types
        $this->db->where_in('resident_type', ['Permanent', 'Temporary']);
        $this->db->group_by('barangay_name, resident_type');

        try {
            $query = $this->db->get();

            if ($query === FALSE) {
                log_message('error', 'Resident type query failed: ' . $this->db->error()['message']);
                return [];
            }

            $result = [];
            foreach ($query->result() as $row) {
                $result[] = [
                    'Barangay Name' => $row->barangay_name,
                    'Resident Type' => $row->resident_type,
                    'Count' => (int) $row->count
                ];
            }

            // Make sure both Permanent and Temporary types are present for each barangay
            if (!empty($result)) {
                $this->_ensure_resident_type_distribution_complete($result);
            }

            return $result;

        } catch (Exception $e) {
            log_message('error', 'Exception in resident type query: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Helper method to ensure both Permanent and Temporary are present for each barangay
     * 
     * @param array &$result Reference to the resident type distribution result array
     */
    private function _ensure_resident_type_distribution_complete(&$result)
    {
        $this->db->reset_query();
        $barangay_query = $this->db->select('barangay_name')
            ->from('barangays')
            ->get();

        if ($barangay_query === FALSE) {
            log_message('error', 'Failed to get barangay list for resident type distribution: ' . $this->db->error()['message']);
            return;
        }

        $barangay_types = [];

        // Index existing data by barangay and resident type
        foreach ($result as $item) {
            $key = $item['Barangay Name'] . '|' . $item['Resident Type'];
            $barangay_types[$key] = true;
        }

        // Check each barangay for missing resident types
        foreach ($barangay_query->result() as $barangay) {
            foreach (['Permanent', 'Temporary'] as $type) {
                $key = $barangay->barangay_name . '|' . $type;
                if (!isset($barangay_types[$key])) {
                    $result[] = [
                        'Barangay Name' => $barangay->barangay_name,
                        'Resident Type' => $type,
                        'Count' => 0
                    ];
                }
            }
        }
    }

    /**
     * Get activity logs with associated usernames
     * 
     * @return array Activity log records with usernames
     */
    public function get_activity_logs()
    {
        // First get all the activity logs
        $this->db->select('activity_logs.*, 
                      admin.username as admin_username, 
                      barangay_secretary.username as secretary_username');
        $this->db->from('activity_logs');

        // Join with admin table
        $this->db->join('admin', 'admin.admin_ID = activity_logs.user_id', 'left');

        // Join with barangay_secretary table
        $this->db->join('barangay_secretary', 'barangay_secretary.secretary_ID = activity_logs.user_id', 'left');

        // Order by most recent logs first
        $this->db->order_by('activity_logs.created_at', 'DESC');

        $query = $this->db->get();

        if ($query === FALSE) {
            log_message('error', 'Failed to get activity logs: ' . $this->db->error()['message']);
            return [];
        }

        $results = $query->result();

        // Process results to determine the correct username for each log
        foreach ($results as $row) {
            if (!empty($row->admin_username)) {
                $row->username = $row->admin_username;
                $row->user_type = 'Admin';
            } else if (!empty($row->secretary_username)) {
                $row->username = $row->secretary_username;
                $row->user_type = 'Secretary';
            } else {
                $row->username = 'Unknown User';
                $row->user_type = 'Unknown';
            }

            // Remove the redundant fields
            unset($row->admin_username);
            unset($row->secretary_username);
        }

        return $results;
    }

    public function log_activity($data)
    {
        try {
            // Transform the input data to match your table structure
            $log_data = [
                'user_id' => $data['user_id'],
                'user_type' => $data['user_type'],
                'action_type' => $data['action_type'],
                'created_at' => $data['created_at'] // Map 'datetime' to 'created_at'
            ];

            return $this->db->insert('activity_logs', $log_data);
        } catch (Exception $e) {
            log_message('error', 'Failed to log activity: ' . $e->getMessage());
            return false;
        }
    }
}