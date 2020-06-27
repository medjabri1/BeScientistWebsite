from reportlab.lib.pagesizes import A4
from reportlab.lib.enums import TA_LEFT
from reportlab.platypus import SimpleDocTemplate, Paragraph, Table, TableStyle
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle, ListStyle
from reportlab.lib.units import cm
import mysql.connector

lines=[]

conn = mysql.connector.connect(host="localhost",user="id13569561_root",password="site-PFE-esto2020", database="id13569561_pfe")
cursor = conn.cursor()
cursor.execute("SELECT article_accepted_id FROM articleaccepted")
rows = cursor.fetchall()
for row in rows:
    cursor.execute("""SELECT title FROM article where article_id = %s""",row)
    t = cursor.fetchone()
    t="("+str(t)+")"
    titre = t[ t.find( '(' )+3 : t.find( ')' )-2 ]
    print(str(titre))
    lines.append(str(titre))


style=ParagraphStyle(name='Normal', alignment=TA_LEFT, fontSize=12, leading=18, fontName='Times-Roman')
style2=ParagraphStyle(name='Normal', alignment=TA_LEFT, fontSize=25, leading=100, leftIndent=100, fontName='Helvetica')

doc=SimpleDocTemplate('uploads/summary.pdf', pageSize=A4)
document=[]

# Add a title, straightforward enough.
document.append(Paragraph('TABLE OF CONTENTS', style2))

# Now, add numbered lines
epic=[]
for line in lines:
    epic.append([Paragraph(str(len(epic)+1), style),
                 Paragraph(line, style)])

t=Table(epic, colWidths=(1.2*cm, None))
t.setStyle(TableStyle([
    ('VALIGN', (0,0), (-1,-1), 'TOP')
]))

document.append(t)

doc.build(document)
conn.close()
