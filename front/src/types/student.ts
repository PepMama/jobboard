// src/types/student.ts
export interface Experience {
  id:           number
  companyName:  string
  jobTitle:     string
  description?: string
  startDate?:   string
  endDate?:     string
}

export interface Education {
  id:           number
  schoolName:   string
  degree:       string
  fieldOfStudy: string
  startDate?:   string
  endDate?:     string
}
